<?php

namespace App\Controller;

use App\Helper\AcaoFactory;
use App\Helper\ReaderSpreadsheet;
use App\Repository\AcaoRejeitadaRepository;
use App\Repository\AcaoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcoesController extends BaseController
{
    public static $SPREADSHEET_FILE_NAME = 'spreadsheet.xlsx';

    public function __construct(private AcaoRepository $acaoRepository)
    {
        parent::__construct();
    }

    #[Route('/acoes', name: 'app_acoes_index')]
    public function index(Request $request): Response
    {
        $offset = ($request->query->getInt('offset', 0) % AcaoRepository::$PAGINATOR_PER_PAGE) !== 0 
            ? 0 
            : $request->query->getInt('offset', 0);

        $order = $request->query->getAlpha('order', 'ASC');
        $paginator = $this->acaoRepository->getAcaoPaginator($offset, $order);
   
        $this->setVariables([
            'acoes' => $paginator,
            'previous' => $offset - AcaoRepository::$PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + AcaoRepository::$PAGINATOR_PER_PAGE),
            'offset' => $offset,
            'order'=> $order
        ]);

        return $this->render('app/acoes/index.html.twig', $this->getVariables());
    }

    #[Route('/acoes/novo', name: 'app_acoes_new_index', methods: ['GET'])]
    public function add()
    {
        return $this->render('app/acoes/new.html.twig', $this->getVariables());
    }

    #[Route('/acoes/novo/{id<\d+>?}', name: 'app_acoes_new_create', methods: ['POST'])]
    public function create(?int $id, AcaoFactory $acaoFactory, Request $request): Response
    {
        if (!is_null($id)) {
            // update
            $acao = $acaoFactory->update($this->acaoRepository->find($id), $request->request->all());

            $this->addFlash(
                'info',
                'Ação atualizada com sucesso!'
            );
        } else {
            // insert
            $acao = $acaoFactory->create($request->request->all());

            $this->addFlash(
                'success',
                'Ação cadastrada com sucesso!'
            );
        }

        $this->acaoRepository->add($acao, true);

        return $this->redirectToRoute('app_acoes_index', [], Response::HTTP_CREATED);
    }

    #[Route('/acoes/alter/{id}', name: 'app_acoes_alter_index', methods: ['GET'])]
    public function alter(int $id): Response
    {
        $acao = $this->acaoRepository->find($id);

        $this->setVariable('acao', $acao);

        return $this->render('app/acoes/new.html.twig', $this->getVariables());
    }

    #[Route('/acoes/delete/{id}', name: 'app_acoes_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $acao = $this->acaoRepository->find($id);

        $this->acaoRepository->remove($acao, true);

        $this->addFlash(
            'info',
            'Ação removida com sucesso!'
        );
        
        return $this->redirectToRoute('app_acoes_index', [], Response::HTTP_TEMPORARY_REDIRECT);
    }

    #[Route('/acoes/load', name: 'app_acoes_load', methods: ['POST'])]
    public function load(AcaoFactory $acaoFactory, AcaoRejeitadaRepository $acaoRejeitadaRepository, ReaderSpreadsheet $readerSpreadsheet, Request $request): Response
    {
        $dataRequest = $readerSpreadsheet->readSpreadsheet(
            $request->files->all()['file'],
            "{$this->getParameter('kernel.project_dir')}/public/uploads"
        );

        $acaoRejeitadaRepository->removeAll();

        $this->acaoRepository->removeAll();

        $arrayAcoes = $acaoFactory->createMany($dataRequest);
        foreach ($arrayAcoes as $acao) {
            $this->acaoRepository->add($acao);
        }

        $this->acaoRepository->flush(); 
        $acaoRejeitadaRepository->flush();       

        $this->addFlash(
            'success',
            'Ações da Planilha cadastradas com sucesso!'
        );

        return $this->redirectToRoute('app_acoes_index', [], Response::HTTP_CREATED);
    }
}

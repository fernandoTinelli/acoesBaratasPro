<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use App\Helper\AcaoRejeitadaFactory;
use App\Repository\AcaoRejeitadaRepository;
use App\Repository\AcaoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcaoRejeitadaController extends BaseController
{
    public function __construct(
        private AcaoRejeitadaRepository $acaoRejeitadaRepository,
        private AcaoRepository $acaoRepository
    ) {
        parent::__construct();
    }

    #[Route('/acao/rejeitada', name: 'app_acao_rejeitada_index')]
    public function index(): Response
    {
        $acoes = $this->acaoRepository->findAllWithLeftJoin();

        foreach ($acoes as $key => $acao) {
            if (!is_null($acao->getAcaoRejeitada())) {
                $acoesRejeitadas[] = $acao;
                unset($acoes[$key]);
            }
        }

        $this->setVariables([
            'acoes' => $acoes,
            'acoesRejeitadas' => $acoesRejeitadas ?? []
        ]);

        return $this->render('/app/acao_rejeitada/index.html.twig', $this->getVariables());
    }

    #[Route('/acao/reijeitada/update', name:'app_acao_rejeitada_update', methods: ['POST'])]
    public function update(AcaoRejeitadaFactory $acaoRejeitadaFactory, Request $request): Response
    {
        $idAcoesRejeitadas = $request->request->all()['_rejecteds'] ?? [];
        $idAcoesRejeitadas = array_flip($idAcoesRejeitadas); // array indexed by id

        $acoes = $this->acaoRepository->findAllWithLeftJoin();

        foreach ($acoes as $acao) {
            if (
                !is_null($acao->getAcaoRejeitada()) && 
                !array_key_exists($acao->getId(), $idAcoesRejeitadas)
            ) {
                $this->acaoRejeitadaRepository->remove($acao->getAcaoRejeitada(), false);
                $acao->setAcaoRejeitada(null);
                $this->acaoRepository->add($acao);
            } elseif (
                is_null($acao->getAcaoRejeitada()) &&
                array_key_exists($acao->getId(), $idAcoesRejeitadas)
            ) {
                $acao->setAcaoRejeitada($acaoRejeitadaFactory->create());
                $this->acaoRepository->add($acao);
            }  
        }

        $this->acaoRepository->flush();
        $this->acaoRejeitadaRepository->flush();

        $this->addFlash(
            'success',
            'Ações Rejeitadas atualizadas com sucesso!'
        );

        return $this->redirectToRoute('app_acao_rejeitada_index', [], Response::HTTP_TEMPORARY_REDIRECT);
    }

    
    #[Route('/acao/reijeitada/{id<\d+>?}', name: 'app_acao_rejeitada_create', methods: ['GET', 'POST'])]
    public function create(?int $id, AcaoRejeitadaFactory $acaoRejeitadaFactory, Request $request): Response
    {
        if (!is_null($id)) { // GET
            $acao = $this->acaoRepository->find($id);

            $this->setVariable('acao', $acao);

            return $this->render('app/acao_rejeitada/new.html.twig', $this->getVariables());
        }

        // POST
        $acao = $this->acaoRepository->find($request->request->getInt('_id'));
        $acao->setAcaoRejeitada($acaoRejeitadaFactory->create($request->request->getAlpha('_motivo')));

        $this->acaoRepository->add($acao);

        $this->addFlash(
            'success',
            "{$acao->getCodigo()} adicionada a lista de rejeição"
        );

        return $this->redirectToRoute('app_lista_acoes_baratas_index');
    }
}

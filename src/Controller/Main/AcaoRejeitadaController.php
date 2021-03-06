<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use App\Helper\AcaoRejeitadaFactory;
use App\Repository\AcaoRejeitadaRepository;
use App\Repository\AcaoRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcaoRejeitadaController extends BaseController
{
    public function __construct(
        private AcaoRejeitadaRepository $acaoRejeitadaRepository,
        private AcaoRepository $acaoRepository,
        private UserRepository $userRepository
    ) {
        parent::__construct();
    }

    #[Route('/acao/rejeitada', name: 'app_acao_rejeitada_index')]
    public function index(): Response
    {
        $user = $this->userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $acoesRejeitadas = $user->getAcaoRejeitadas()->toArray();
        $acoesRejeitadasIndexada = array();
        foreach ($acoesRejeitadas as $acao) {
            $acoesRejeitadasIndexada[$acao->getId()] = $acao;
        }

        $acoes = $this->acaoRepository->findAll();

        foreach ($acoes as $key => $acao) {
            if (array_key_exists($acao->getId(), $acoesRejeitadasIndexada)) {
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

        $user = $this->userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        
        $acoesRejeitadas = $user->getAcaoRejeitadas()->toArray();
        $acoesRejeitadasIndexada = array();
        foreach ($acoesRejeitadas as $acao) {
            $acoesRejeitadasIndexada[$acao->getId()] = $acao;
        }

        foreach ($idAcoesRejeitadas as $id) {
            if (!array_key_exists($id, $acoesRejeitadasIndexada)) {
                // Criar nova a????o rejeitada
                $acaoRejeitada = $acaoRejeitadaFactory->create($user, $this->acaoRepository->find($id), '');
                $this->acaoRejeitadaRepository->add($acaoRejeitada, false);
            } else {
                // Retira da lista. O que sobrar, n??o ser?? mais rejeitada
                unset($acaoRejeitadaRepository[$id]);
            }
        }

        // Remover do banco o que sobrou na lista de acoesRejeitadasIndexada
        foreach ($acoesRejeitadasIndexada as $acaoRejeitada) {
            $this->acaoRejeitadaRepository->remove($acaoRejeitada, false);
        }

        $this->acaoRejeitadaRepository->flush();       

        $this->addFlash(
            'success',
            'A????es Rejeitadas atualizadas com sucesso!'
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
        $user = $this->userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $acao = $this->acaoRepository->find($request->request->getInt('_id'));
        $acaoRejeitada = $acaoRejeitadaFactory->create($user, $acao, $request->request->getAlpha('_motivo'));

        $this->acaoRejeitadaRepository->add($acaoRejeitada);

        $this->addFlash(
            'success',
            "{$acao->getCodigo()} adicionada a lista de rejei????o"
        );

        return $this->redirectToRoute('app_lista_acoes_baratas_index');
    }
}

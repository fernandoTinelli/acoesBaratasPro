<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\AcaoRejeitada;
use App\Helper\AcaoRejeitadaFactory;
use App\Repository\AcaoRejeitadaRepository;
use App\Repository\AcaoRepository;
use App\Trait\DefaultVariablesControllers;
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
        $acoes = $this->acaoRepository->findAllWithOrWithoutJoin();

        foreach ($acoes as $key => $acao) {
            if (!is_null($acao->getAcaoRejeitada())) {
                $acoesRejeitadas[] = $acao;
                unset($acoes[$key]);
            }
        }

        $this
            ->setVariable('acoes', $acoes)
            ->setVariable('acoesRejeitadas', $acoesRejeitadas ?? []);

        return $this->render('/app/acao_rejeitada/index.html.twig', $this->getVariables());
    }

    #[Route('/acao/reijeitada/update', name:'app_acao_rejeitada_update', methods: ['POST'])]
    public function update(AcaoRejeitadaFactory $acaoRejeitadaFactory, Request $request): Response
    {
        $idAcoesRejeitadas = $request->request->all()['_rejecteds'] ?? [];

        $idAcoesRejeitadas = array_flip($idAcoesRejeitadas); // array indexed by id

        $acoes = $this->acaoRepository->findAllWithOrWithoutJoin();

        // var_dump($acoes); exit();

        foreach ($acoes as $acao) {
            if (array_key_exists($acao->getId(), $idAcoesRejeitadas) && is_null($acao->getAcaoRejeitada())) {
                $acao->setAcaoRejeitada($acaoRejeitadaFactory->create());
            } else {
                if (!is_null($acao->getAcaoRejeitada()) && !array_key_exists($acao->getId(), $idAcoesRejeitadas)) {
                    $this->acaoRejeitadaRepository->remove($acao->getAcaoRejeitada(), false);
                }
                $acao->setAcaoRejeitada(null);
            }   
            
            $this->acaoRepository->add($acao, false);
        }

        $this->acaoRepository->flush();
        $this->acaoRejeitadaRepository->flush();

        $this->addFlash(
            'success',
            'Ações Rejeitadas atualizadas com sucesso!'
        );

        return $this->redirectToRoute('app_acao_rejeitada_index', [], Response::HTTP_TEMPORARY_REDIRECT);
    }
}

<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use App\Entity\Acao;
use App\Helper\ArrayUtils;
use App\Repository\AcaoRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListaAcoesBaratasController extends BaseController
{
    private static $MIN_LIQUIDEZ_ACCEPTED = 200000;
    private static $MIN_MARGEM_EBIT_ACCPETED = 0;
    private static $ARR_ACOES_NUM_CODIGO_RECUSADAS = [33];

    public function __construct(private AcaoRepository $acaoRepository)
    {
        parent::__construct();
    }

    #[Route('/acoes/baratas', name: 'app_lista_acoes_baratas_index')]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        
        $acoesAccepteds = $this->getAcoesAccepteds(
            $this->acaoRepository->findAll(),
            $user->getAcaoRejeitadas()->toArray()
        );

        $offset = ($request->query->getInt('offset', 0) % AcaoRepository::$PAGINATOR_PER_PAGE) !== 0 
            ? 0 
            : $request->query->getInt('offset', 0);

        $acoesStar = [];
        foreach ($user->getStars() as $star) {
            $acoesStar[$star->getAcao()->getId()] = true;
        }

        // $this->setVariableTitle('Lista de Ações Baratas da Bolsa');
        $this->setVariables([
            'acoes' => $acoesAccepteds,
            'acoesShow' => array_slice($acoesAccepteds, $offset, min(count($acoesAccepteds), AcaoRepository::$PAGINATOR_PER_PAGE)),
            'previous' => $offset - AcaoRepository::$PAGINATOR_PER_PAGE,
            'next' => min(count($acoesAccepteds), $offset + AcaoRepository::$PAGINATOR_PER_PAGE),
            'offset' => $offset,
            'stars' => $acoesStar
        ]);

        // dd($this->getVariables($request));

        return $this->render('app/lista_acoes_baratas/index.html.twig', $this->getVariables($request));
    }

    private function getAcoesAccepteds(array $acoes, array $acoesRejeitadas): array
    {
        $acoesAccepteds = array();

        $acoesRejeitadasIndexada = array();
        foreach ($acoesRejeitadas as $acaoRejeitada) {
            $acoesRejeitadasIndexada[$acaoRejeitada->getAcao()->getId()] = $acaoRejeitada;
        }

        foreach ($acoes as $acao) {
            if (
                $acao->getLiquidez() >= ListaAcoesBaratasController::$MIN_LIQUIDEZ_ACCEPTED &&
                $acao->getMargemEbit() > ListaAcoesBaratasController::$MIN_MARGEM_EBIT_ACCPETED &&
                !$this->isAcaoNumCodigoRecusada($acao->getCodigo()) &&
                !$this->isAcaoRecusada($acao, $acoesRejeitadasIndexada)
            ) {
                $codigo = substr($acao->getCodigo(), 0, 3); // Get only the alpha characteres
                if (
                    array_key_exists($codigo, $acoesAccepteds) &&
                    $acao->getLiquidez() < $acoesAccepteds[$codigo]->getLiquidez()
                ) {
                    continue;
                }
                
                $acoesAccepteds[$codigo] = $acao;
            }
        }

        usort($acoesAccepteds, fn(Acao $acao1, Acao $acao2) => $acao1->getEvEbit() <=> $acao2->getEvEbit());

        return $acoesAccepteds;
    }

    private function isAcaoRecusada(Acao $acao, array $acoesRejeitadas): bool
    {
        return array_key_exists($acao->getId(), $acoesRejeitadas);
    }

    private function isAcaoNumCodigoRecusada(string $codigo): bool
    {
        return in_array(substr($codigo, 4, 2), self::$ARR_ACOES_NUM_CODIGO_RECUSADAS);
    }
}

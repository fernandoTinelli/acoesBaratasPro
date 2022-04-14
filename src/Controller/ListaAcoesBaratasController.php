<?php

namespace App\Controller;

use App\Entity\Acao;
use App\Repository\AcaoRepository;
use App\Trait\DefaultVariablesControllers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListaAcoesBaratasController extends AbstractController
{
    use DefaultVariablesControllers;

    private static $MIN_LIQUIDEZ_ACCEPTED = 200000;
    private static $MIN_MARGEM_EBIT_ACCPETED = 0;
    private static $ARR_ACOES_NUM_CODIGO_RECUSADAS = [33];

    private AcaoRepository $acaoRepository;

    public function __construct(AcaoRepository $acaoRepository)
    {
        $this->acaoRepository = $acaoRepository;
    }

    #[Route('/lista/acoes/baratas', name: 'app_lista_acoes_baratas_index')]
    public function index(Request $request): Response
    {
        $variables = $this->defaultVariables();

        $acoes = $this->acaoRepository->findAll();
        $acoesAccepteds = array();

        foreach ($acoes as $acao) {
            if (
                $acao->getLiquidez() >= ListaAcoesBaratasController::$MIN_LIQUIDEZ_ACCEPTED &&
                $acao->getMargemEbit() > ListaAcoesBaratasController::$MIN_MARGEM_EBIT_ACCPETED &&
                !$this->isAcaoNumCodigoRecusada($acao->getCodigo()) &&
                !$this->isAcaoRecusada($acao->getCodigo())
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

        usort($acoesAccepteds, [
            ListaAcoesBaratasController::class,
            "sortAcoes"
        ]);

        $offset = $request->query->getInt('offset', 0);
        $variables = $this->defaultVariables();
        $variables['acoes'] = $acoesAccepteds;
        $variables['acoesShow'] = array_slice($acoesAccepteds, $offset, min(count($acoesAccepteds), AcaoRepository::$PAGINATOR_PER_PAGE));
        $variables['previous'] = $offset - AcaoRepository::$PAGINATOR_PER_PAGE;
        $variables['next'] = min(count($acoesAccepteds), $offset + AcaoRepository::$PAGINATOR_PER_PAGE);
        $variables['offset'] = $offset;

        return $this->render('app/lista_acoes_baratas/index.html.twig', $variables);
    }

    static private function sortAcoes(Acao $acao1, Acao $acao2) {
        if ($acao1->getEvEbit() == $acao2->getEvEbit()) {
          return 0;
        }
    
        return ($acao1->getEvEbit() < $acao2->getEvEbit()) ? -1 : 1;
    }

    private function isAcaoRecusada(string $strCodigoAcao) {
        return false;
        // return in_array($strCodigoAcao, self::$ARR_ACOES_RECUSADAS);
    }

    private function isAcaoNumCodigoRecusada(string $codigo): bool {
        return in_array(substr($codigo, 4, 2), self::$ARR_ACOES_NUM_CODIGO_RECUSADAS);
    }
}

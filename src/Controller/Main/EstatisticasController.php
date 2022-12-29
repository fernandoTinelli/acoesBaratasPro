<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use App\Model\EstatisticasModel;
use App\Repository\AcaoRepository;
use App\Repository\TransacaoRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstatisticasController extends BaseController
{
    private EstatisticasModel $estatisticasModel;

    public function __construct(EstatisticasModel $estatisticasModel)
    {
        $this->estatisticasModel = $estatisticasModel;
    }

    #[Route('/estatisticas', name: 'app_estatisticas_index')]
    public function index(): Response
    {

        $top5AcoesCompradasMes = $this->estatisticasModel->getTop5AcoesCompradasMes();

        return $this->render('app/estatisticas/index.html.twig', $this->getVariables());
    }
}

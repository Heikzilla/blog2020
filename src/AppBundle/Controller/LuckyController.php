<?php
// src/AppBundle/Controller/LuckyController.php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LuckyController extends Controller
{
    /**
     * @Route("/lottery/numbers")
     */
    public function numberAction()
    {
        $a = 0;
        while ($a <= 6) {
            $numberArr[$a] = random_int(1,49);
            $a++;
        }
        asort($numberArr);
        $lotteryNumber = implode(' ', $numberArr);

        return $this->render('lottery/numbers.html.twig',[
            'lotteryNumbers' => $lotteryNumber, 
        ]);
    }
}

?>
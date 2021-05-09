<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;

class BoardingController extends AbstractController
{
    public $missed_seat;
    public $highest_seat_id;
    public $lowest_seat_id;
	
    /**
     * @Route("/", name="boarding")
     */
    public function show(): Response
    {
        $this->missed_seat = 0;
        $this->highest_seat_id = 0;
        $this->lowest_seat_id = 0;

        $list_array = $this->getList();
        
        $seat_array = array();
        foreach( $list_array as $line )
        {
            $seat_id = $this->getSeatNumber( $line );
            $seat_array[] = $seat_id;
        }

        //sort the array
        rsort( $seat_array );
        $this->highest_seat_id = max($seat_array);
        $this->lowest_seat_id = min($seat_array);

        // find the missing seat no
        for( $i = $this->lowest_seat_id + 1; $i < $this->highest_seat_id - 1; $i++ )
        {
            if( !array_search( $i, $seat_array) )
            {
                $this->missed_seat = $i;
            }
        }

        return $this->render('boarding/show.html.twig', [
            'highest_seat_id' => $this->highest_seat_id,
            'missed_seat' => $this->missed_seat
        ]);
    }

    public function getSeatNumber($line)
    {
        // prepare binary string
        $code = preg_replace( "/B/", "1", $line );
        $code = preg_replace( "/F/", "0", $code );
        $code = preg_replace( "/R/", "1", $code );
        $code = preg_replace( "/L/", "0", $code );
        
        // convert binaries to decimals
        $row  = bindec( substr( $code, 0, 7 ) );
        $column = bindec( substr( $code, 7, 3 ) );

        // calculate the seat ID
        $seat_id = $row * 8 + $column;
            
        return $seat_id;
    }

    private function getList()
    {
		$projectRoot = $this->getParameter('kernel.project_dir');
		$filePath = $projectRoot.'\var\input.csv';
		if(file_exists($filePath)){
			$lines =file($filePath);

			$arrList = explode(',',$lines[0]);
			
		}

        return $arrList;
    }
}

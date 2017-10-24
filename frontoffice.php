<?php
/**
 * Routage et actions Front office de l'application

 * @copyright  2015-2017 Telecom SudParis
 * @license    "MIT/X" License - cf. LICENSE file at project root
 */

/**
 * @var \Closure $circuitlist_action
 * 
 * Liste tous les circuits
 */
$circuitlist_action = function () use ($app) 
{
	$circuitslist = get_all_circuits (FALSE);
	
	return $app ['twig']->render ( 'frontoffice/circuitslist.html.twig', [
			'circuitslist' => $circuitslist
	] );
};
$app->get ( '/circuit', $circuitlist_action )
    ->bind ( 'circuitlist' );

// circuitshow : affiche les détails d'un circuit
$app->get ( '/circuit/{id}', 
		function ($id) use ($app) 
		{
			$circuit = get_circuit_by_id ( $id );
	
			return $app ['twig']->render ( 'frontoffice/circuitshow.html.twig', [ 
					'id' => $id,
					'circuit' => $circuit,
                    'admin' => false
				] );
		}
)->bind ( 'circuitshowuser' );

// programmationlist : liste tous les circuits programmés
$app->get ( '/programmation', 
		function () use ($app) 
		{
			$programmationslist = get_all_programmations ();
	
			return $app ['twig']->render ( 'frontoffice/programmationslist.html.twig', [ 
					'programmationslist' => $programmationslist 
				] );
		}
)->bind ( 'programmationlist' );

// programmationshow : affiche les détails d'une programmation
$app->get ( '/programmation/{id}',
		function ($id) use ($app) 
		{
			$programmation = get_programmation_by_id ( $id );
			
			return $app ['twig']->render ( 'frontoffice/programmationshow.html.twig', [
					'id' => $id,
					'programmation' => $programmation
			] );
		}
)->bind ( 'programmationshow' );


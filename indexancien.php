<?php

/**
 * Application d'exemple Agence de voyages Silex
 */

// require_once __DIR__.'/vendor/autoload.php';
$vendor_directory = getenv ( 'COMPOSER_VENDOR_DIR' );
if ($vendor_directory === false) {
	$vendor_directory = __DIR__ . '/vendor';
}
require_once $vendor_directory . '/autoload.php';

// Initialisations
$app = require_once 'initapp.php';

require_once 'agvoymodel.php';

// Routage et actions


// circuitlist : Liste tous les circuits
$app->get ( '/circuit',
    function () use ($app) 
    {
    	$circuitslist = get_all_circuits ();
    	// print_r($circuitslist);
    	
    	return $app ['twig']->render ( 'circuitslist.html.twig', [
    			'circuitslist' => $circuitslist,
                'admin'=>false
    	] );
    }
)->bind ( 'circuitlistuser' );

// circuitshow : affiche les détails d'un circuit
$app->get ( '/circuit/{id}', 
	function ($id) use ($app) 
	{
		$circuit = get_circuit_by_id ( $id );
		// print_r($circuit);
		$programmations = get_programmations_by_circuit_id ( $id );
		//$circuit ['programmations'] = $programmations;

		return $app ['twig']->render ( 'circuitshow.html.twig', [ 
				'id' => $id,
				'circuit' => $circuit,
                'admin'=>false
			]);
	}
)->bind ( 'circuitshowuser' );

$app->get ( '/admin/circuit/{id}',
    function ($id) use ($app)
    {
        $circuit = get_circuit_by_id ( $id );
        // print_r($circuit);
        $programmations = get_programmations_by_circuit_id ( $id );
        //$circuit ['programmations'] = $programmations;

        return $app ['twig']->render ( 'circuitshow.html.twig', [
            'id' => $id,
            'circuit' => $circuit,
            'admin'=>true
        ]);
    }
)->bind ( 'circuitshowadmin' );

/*
$app->get ( '/admin/circuitnew',
    function () use ($app)
    {

        return $app ['twig']->render ( 'circuitnew.html.twig', [

            'admin'=>true
        ]);
    }
)->bind ( 'circuitnew' );


$app->match('/admin/circuitnew', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    $data = array(

        'name' => 'Your name',
        'email' => 'Your email',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('name')
        ->add('email')
        ->add('billing_plan', 'choice', array(
            'choices' => array(1 => 'free', 2 => 'small_business', 3 => 'corporate'),
            'expanded' => true,
        ))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        // do something with the data

        // redirect somewhere
        return $app->redirect('...');
    }

    // display the form
    return $app['twig']->render('circuitnew.html.twig', array('form' => $form->createView()));
});

*/
//---------- Formulaires Circuit

/*
 * Fonction utilitaire créant un formulaire pour un Circuit
 */
function circuitnewget_form($app)
{
    // variante PHP classique "verbeuse"
    //     $formbuilder = $app['form.factory']->createBuilder(FormType::class);
    //     $formbuilder->add('description');
    //     $formbuilder->add('paysDepart');
    //     $formbuilder->add('villeDepart');
    //     $formbuilder->add('villeArrivee');
    //     $formbuilder->add('dureeCircuit');
    //     $form = $formbuilder->getForm();

    // On préfère une variante compacte
    $form = $app['form.factory']->createBuilder(FormType::class)
        ->add('description')
        ->add('paysDepart')
        ->add('villeDepart')
        ->add('villeArrivee')
        ->add('dureeCircuit')
        ->getForm();
    return $form;
}

/**
 * @var \Closure $admin_circuitnew_getaction
 *
 * Affichage d'un formulaire d'ajout de nouveau circuit
 *
 * Voir $admin_circuitnew_postaction pour gestion du POST correspondant
 */
$admin_circuitnew_getaction = function() use ($app)
{
    $formulaire = circuitnewget_form($app);

    $formview = $formulaire->createView();

    // display the form
    return $app['twig']->render('backoffice/circuitnew.html.twig',
        array('formulaire' => $formview)
    );
};
// GET
$app->get('/admin/circuitnew', $admin_circuitnew_getaction)
    ->bind('admin_circuitnew');

/**
 * @var \Closure $admin_circuitnew_postaction
 *
 * Soumission d'un formulaire d'ajout de nouveau circuit (POST)
 */
$admin_circuitnew_postaction = function(Request $request) use ($app)
{
    $form = circuitnewget_form($app);

    $form->handleRequest($request);

    // Data is supposed to be valid, but we actually don't use validators
    if ($form->isValid())
    {
        $data = $form->getData();

        add_circuit($data['description'],
            $data['paysDepart'],
            $data['villeDepart'],
            $data['villeArrivee'],
            $data['dureeCircuit']
        );

        // Make sure message will be displayed after redirect
        $app['session']->getFlashBag()->add('message', 'circuit bien ajouté');

        $url = $app["url_generator"]->generate("admin_circuitlist");
        return $app->redirect($url);
    }
    // for now, don't manage the case of non-valid data
};
// POST
$app->post('/admin/circuitnew', $admin_circuitnew_postaction);

/**
 * @var \Closure $admin_circuitmodify_action
 *
 * Gestion d'un formulaire de modification d'un circuit (gère GET et POST dans même
 * méthode)
 *
 * $id : identifiant du circuit
 */
$admin_circuitmodify_action = function (Request $request, $id) use ($app) {

    $circuit = get_circuit_by_id($id);

    // prefill the form with values of the Circuit
    $form = $app['form.factory']->createBuilder(FormType::class,
        $circuit)
        ->add('description')
        ->add('paysDepart')
        ->add('villeDepart')
        ->add('villeArrivee')
        ->add('dureeCircuit')
        ->getForm();

    $form->handleRequest($request);

    // if form was posted
    if ($form->isValid()) {

        save_circuit($circuit);

        $app['session']->getFlashBag()
            ->add('message', 'circuit modifé');

        return $app->redirect($app["url_generator"]->generate("admin_circuitshow",
            array(
                'id' => $circuit->getId()
            )));
    }

    // display the form (GET or failed POST)
    return $app['twig']->render('backoffice/circuitmodify.html.twig',
        array(
            'formulaire' => $form->createView()
        ));
};
// handle both GET and POST
$app->match('/admin/circuitmodify/{id}', $admin_circuitmodify_action)
    ->bind('admin_circuitmodify');

/**
 * @var \Closure $admin_circuitdelete_action
 *
 * Gestion de la suppression d'un circuit (DELETE)
 */
$admin_circuitdelete_action = function ($id) use ($app) {

    remove_circuit_by_id($id);

    $app['session']->getFlashBag()->add('message', 'circuit suprimé');

    return $app->redirect($app["url_generator"]->generate("admin_circuitlist"));

};
// DELETE (mais grâce à Request::enableHttpMethodParameterOverride)
$app->delete('/admin/circuit/{id}', $admin_circuitdelete_action)
    ->bind('admin_circuitdelete');









//Route standard
$app->get ( '/',
function() use ($app)
               {
               	return $app ['twig']->render ( 'index.html.twig' );
               }
           )->bind ('baselayout');
// programmationlist : liste tous les circuits programmés

$app->get ( '/admin/programmation',

    function () use ($app)
    {
        $programmationslist = get_all_programmations ();
        // print_r($programmationslist);

        return $app ['twig']->render ( 'programmationslist.html.twig', [
            'programmationslist' => $programmationslist
        ] );
    }

)->bind ( 'programmationlistadmin' );

$app->run ();



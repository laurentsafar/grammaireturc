<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Partie;
use App\Entity\Joueurs;
use App\Entity\Mots;
use App\Form\InitPartieType;
use App\Form\AddJoueurType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


use function YoastSEO_Vendor\GuzzleHttp\Promise\each;

class PhraseSimpleController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request): Response
    {
        $partie = new Partie();
        $form = $this->createForm(InitPartieType::class,$partie);
       
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            

          
            $partie = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($partie);
            $entityManager->flush();
            
            $this->session->set('partieTurc', $partie->getId());
            return $this->redirectToRoute('synthese-partie',['idpartie'=>$partie->getId()]);
        }

        return $this->render('phrase_simple/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/synthese-partie/{idpartie}",name="synthese-partie")
     * 
     */
    public function synthesePartie(Request $request,int $idpartie):Response{

   
        $joueur = new Joueurs();
        $partie = $this->getDoctrine()
            ->getRepository(Partie::class)
            ->find($idpartie);
        $joueurs = $this->getDoctrine()
        ->getRepository(Joueurs::class)
        ->findByPartie($idpartie);
        
        $form = $this->createForm(AddJoueurType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $joueur = $form->getData();
            $joueur->setPartie($partie);
            $nbre = $partie->getNbrjoueurs();
            $partie->setNbrjoueurs($nbre+1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($joueur);
            $entityManager->flush();
            return $this->redirectToRoute('synthese-partie',['idpartie'=>$partie->getId()]);  
        }
        return $this->render('phrase_simple/synthese-partie.html.twig', [
            'form' => $form->createView(),
            'joueurs'=>$joueurs,
            'partie'=>$partie

        ]);
        }
    /**
     * @Route("/jouerinit/{id}",name="jouerinit")
     * 
     */
    public function jouerinit(int $id):Response
    {   
        $partie = $this->getDoctrine()
            ->getRepository(Partie::class)
            ->find($id);
        $joueurs = $this->getDoctrine()
        ->getRepository(Joueurs::class)
        ->findByPartie($id);
        $partie->setCycletour(0);
        $partie->setTour($partie->getTour()+1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($partie);
        $entityManager->flush();
        $cycleTour = 0;
        $liste = [];
        foreach ($joueurs as $joueur) {
        array_push($liste,$joueur->getId());        }

        $new = $this->shuffle_assoc($liste);
        $partie->setOrdre($new);
        $entityManager->persist($partie);
        $entityManager->flush();
        
        return $this->redirectToRoute('jeu',['id'=>$partie->getId(),"test"=>0]); 

    }

    

    /**
     * @Route("/jeu/{id}/{test}",name="jeu")
     */
    public function jeu(Int $id,Int $test){
        
        $partie = $this->getDoctrine()
            ->getRepository(Partie::class)
            ->find($id);
        $cycleTour=$partie->getCycletour();
        $ordrejoueurs = $partie->getOrdre();

        if($test==1){
            $cycleprecedent = $cycleTour - 1;
            $joueuranoter = $this->getDoctrine()
            ->getRepository(Joueurs::class)
            ->find($ordrejoueurs[$cycleprecedent]);
            $score = $joueuranoter->getScore();
            $joueuranoter->setScore($score+1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($joueuranoter);
            $entityManager->flush();
        }

        if($cycleTour<count($ordrejoueurs)){
        $joueur = $this->getDoctrine()
        ->getRepository(Joueurs::class)
        ->find($ordrejoueurs[$cycleTour]);
        

        $cycleTour ++;
        $partie->setCycletour($cycleTour);
        
        $ordreaffiche=[];
        for($i=0;$i<count($ordrejoueurs);$i++){
            $joueuraffiche = $this->getDoctrine()
            ->getRepository(Joueurs::class)
            ->find($ordrejoueurs[$i]); 
            array_push($ordreaffiche,$joueuraffiche);
        }

        
        $tempsjeu = $this->determineTemps($partie);
        $typejeu = $this->determineType($partie);
        $personnejeu = $this->determinePersonne($partie);
        $partie->setLasttemps($tempsjeu);
        $partie->setLastpersonne($personnejeu);
        $partie->setLasttype($typejeu);
        $mots =  $joueuraffiche = $this->getDoctrine()
        ->getRepository(Mots::class)
        ->findAll();
        $motjeu = $this->determineMot($partie,$mots);
        $partie->setLastmot($motjeu->getId());
        $traduction = $this->traduire($motjeu->getTurc(),$tempsjeu,$typejeu,$personnejeu);
        $phrase = $this->phrasefrancaise($motjeu,$tempsjeu,$typejeu,$personnejeu);
        //dd($traduction);

        

        $entityManager = $this->getDoctrine()->getManager();
        
        $entityManager->persist($partie);
        $entityManager->flush();

        return $this->render('phrase_simple/jeu.html.twig', [
            'resultats'=>$ordreaffiche,
            'joueur'=>$joueur,
            'traduction'=>$traduction,
            'aide'=>$motjeu->getTurc(),
            'partie'=>$partie,
            'phrase'=>$phrase
           

        ]);
        }else{
            
            return $this->redirectToRoute('jouerinit',['id'=>$id]); 
        }
       



    }

    function determineMot($partie,$mots){
        $lastmot = $partie->getLastmot();

        $motsArray=[];
        foreach ($mots as $mot) {
          $motsArray[$mot->getId()]=$mot;
        }
        if($lastmot != null){
            if(count($motsArray)>1){
                unset($motsArray[$lastmot]);  
            }
        }
        $new = $this->shuffle_assoc($motsArray); 

        return $new[0];
    }


    function determineTemps($partie){
        $lasttemps = $partie->getLasttemps();
        $temps =[];
        if($partie->getPasse()==true){
            array_push($temps,1);
        }
        if($partie->getPresent()==true){
            array_push($temps,0);
        }
        if($partie->getFutur()==true){
            array_push($temps,2);
        }
        if($lasttemps != null){
            if(count($temps)>1){
                unset($temps[array_search($lasttemps, $temps)]);  
            }
        }
        $new = $this->shuffle_assoc($temps); 
        return $new[0];
    }

    function determineType($partie){
        $lasttype = $partie->getLasttype();
        $type =[];
        if($partie->getAffirmation()==true){
            array_push($type,1);
        }
        if($partie->getQuestion()==true){
            array_push($type,3);
        }
        if($partie->getNegation()==true){
            array_push($type,2);
        }
        if($lasttype != null){
            if(count($type)>1){
                unset($type[array_search($lasttype, $type)]);  
            }
        }
        $new = $this->shuffle_assoc($type); 
        return $new[0];
    }

    function determinePersonne($partie){
        $lastpersonne = $partie->getLastpersonne();
        $personne =[];
        if($partie->getJe()==true){
            array_push($personne,1);
        }
        if($partie->getTu()==true){
            array_push($personne,2);
        }
        if($partie->getIl()==true){
            array_push($personne,3);
        }
        if($partie->getNous()==true){
            array_push($personne,4);
        }
        if($partie->getVous()==true){
            array_push($personne,5);
        }
        if($partie->getIls()==true){
            array_push($personne,6);
        }
        if($lastpersonne != null){
            if(count($personne)>1){
                unset($personne[array_search($lastpersonne, $personne)]);  
            }
        }
        $new = $this->shuffle_assoc($personne); 
        return $new[0];
    }



    function shuffle_assoc($array) {
        $keys = array_keys($array);

        shuffle($keys);

        foreach($keys as $key) {
            $new[] = $array[$key];
        }

        $array = $new;

        return $array;
    }

  
  function traduire($mot,$temps,$type,$personne){
  
  //temps = 0present -1passe -2futur 
  //type = 1affirm - 2neg - 3question;
  //personne = 1;
  
  
    
    $fin1 = substr($mot, -1);
    $fin2 = substr($mot,-2,1);
    $liaison = "";
    $passe = 'd';
    $kconsonne = false;
    $consonne = false;
    switch ($fin1) {
    case 'a':
    case '??':
      $voyelle = '??';
      $pvoyelle = 'a';
      $dvoyelle = '??';
      $liaison = 'y';
      break;
    case 'e':
    case 'i':
      $voyelle = 'i';
      $pvoyelle = 'e';
      $dvoyelle = 'i';
      $liaison = 'y';
      break;
    case 'o':
    case 'u':
      $voyelle = 'u';
      $pvoyelle = 'a';
      $dvoyelle = '??';
      $liaison = 'y';
      break;
    case '??':
    case '??':
      $voyelle = '??';
      $pvoyelle = 'e';
      $dvoyelle = 'i';
      $liaison = 'y';
      break;
    case 'k':
      $passe = 't';
      $consonne = 'true';
      $kconsonne ='true';
      break;
    case 't':
    case 'p':
    case '??':
    case 'h':
    case 'f':
    case 's':
    case '??':
      $passe = 't';
      $consonne = 'true';
      break;
    default : 
      $consonne = 'true';
      break;
      
  }
  
  if($consonne == 'true'){
    
    switch ($fin2) {
    case 'a':
    case '??':
      $voyelle = '??';
      $pvoyelle = 'a';
      $dvoyelle = '??';
      
      break;
    case 'e':
    case 'i':
      $voyelle = 'i';
      $pvoyelle = 'e';
      $dvoyelle = 'i';
      
      break;
    case 'o':
    case 'u':
      $voyelle = 'u';
      $pvoyelle = 'a';
      $dvoyelle = '??';
      
      break;
    case '??':
    case '??':
      $voyelle = '??';
      $pvoyelle = 'e';
      $dvoyelle = 'i';
      
      break;
    }
  
  }
  
  
  
  switch ($temps) {
    // pass??
    case 1 :
      switch($type){
        // passe affirm
        case 1:
          $traduction = $mot;
          switch($personne){
            case 1:
              $traduction = $traduction.$liaison.$passe.$voyelle."m";
              break;
            case 2:
              $traduction = $traduction.$liaison.$passe.$voyelle."n";
              break;
            case 3:
              $traduction = $traduction.$liaison.$passe.$voyelle;
              break;
            case 4:
              $traduction = $traduction.$liaison.$passe.$voyelle."k";
              break;
            case 5:
              $traduction = $traduction.$liaison.$passe.$voyelle."n".$voyelle."z";
              break;
            case 6:
              $traduction = $traduction."l".$pvoyelle."r"."d".$dvoyelle;
              break;
          }
          break;
        // passe neg
        case 2:
          $traduction = $mot." de??il";
          switch($personne){
            case 1:
              $traduction = $traduction."dim";
              break;
            case 2:
              $traduction = $traduction."din";
              break;
            case 3:
              $traduction = $traduction."di";
              break;
            case 4:
              $traduction = $traduction."dik";
              break;
            case 5:
              $traduction = $traduction."diniz";
              break;
            case 6:
              $traduction = $traduction."diler";
              break;
          }
          break;
        // passe quest
        case 3:
          $traduction = $mot. " m".$voyelle."yd".$voyelle;
          switch($personne){
            case 1:
              $traduction = $traduction."m";
              break;
            case 2:
              $traduction = $traduction."n";
              break;
            case 3:
              $traduction = $traduction;
              break;
            case 4:
              $traduction = $traduction."k";
              break;
            case 5:
              $traduction = $traduction."n".$voyelle."z";
              break;
            case 6:
              $traduction = $traduction."l".$pvoyelle."r";
              break;
          }
  
          break;
      }
    break;
  
    // pr??sent 
    case 0 :
      switch($type){
        // present affirm
        case 1:
          $traduction = $mot;
          switch($personne){
            case 1:
              if($kconsonne=='true'){
                $traduction = $traduction.substr($traduction,0,-1);
                $traduction = $traduction."??".$voyelle."m";
              }else{
                $traduction = $traduction.$liaison.$voyelle."m";
              }
              
              break;
            case 2:
              $traduction = $traduction."s".$voyelle."n";
              break;
            case 3:
              $traduction = $traduction;
              break;
            case 4:
            if($kconsonne=='true'){
                $traduction = $traduction.substr($traduction,0,-1);
                $traduction = $traduction."??".$voyelle."z";
              }else{
                $traduction = $traduction.$liaison.$voyelle."z";
              }
              
              break;
            case 5:
              $traduction = $traduction."s".$voyelle."n".$voyelle."z";
              break;
            case 6:
              $traduction = $traduction."l".$pvoyelle."r";
              break;
          }
          break;
        // present nega
        case 2:
          $traduction = $mot." de??il";
          switch($personne){
            case 1:
              $traduction = $traduction."im";
              break;
            case 2:
              $traduction = $traduction."sin";
              break;
            case 3:
              $traduction = $traduction;
              break;
            case 4:
              $traduction = $traduction."iz";
              break;
            case 5:
              $traduction = $traduction."siniz";
              break;
            case 6:
              $traduction = $traduction."ler";
              break;
          }
          break;
        
        //present question
        case 3:
          $traduction = $mot;
          switch($personne){
            case 1:
              $traduction = $traduction. " m".$voyelle."y".$voyelle."m ?";
              break;
            case 2:
              $traduction = $traduction. " m".$voyelle."s".$voyelle."n ?";
              break;
            case 3:
              $traduction = $traduction. " m".$voyelle." ?";
              break;
            case 4:
              $traduction = $traduction. " m".$voyelle."y".$voyelle."z ?";
              break;
            case 5:
              $traduction = $traduction. " m".$voyelle."s".$voyelle."n".$voyelle."z ?";
              break;
            case 6:
              $traduction = $traduction."l".$pvoyelle."r". " m".$voyelle ." ?";
              break;
          }
          break;
      }
    break;
  
    //futur
    case 2 :
      switch($type){
        //futur affirm
        case 1:
          $traduction = $mot;
          switch($personne){
            case 1:
              $traduction = $traduction." olaca????m";
              break;
            case 2:
              $traduction = $traduction. " olacaks??n";
              break;
            case 3:
              $traduction = $traduction. " olacak";
              break;
            case 4:
              $traduction = $traduction. " olaca????z";
              break;
            case 5:
              $traduction = $traduction. " olacaks??n??z";
              break;
            case 6:
              $traduction = $traduction. " olacaklar";
              break;
          }
          break;
        //futur negation
        case 2:
          $traduction = $mot;
          switch($personne){
            case 1:
              $traduction = $traduction." olmayaca????m";
              break;
            case 2:
              $traduction = $traduction. " olmayacaks??n";
              break;
            case 3:
              $traduction = $traduction. " olmayacak";
              break;
            case 4:
              $traduction = $traduction. " olmayaca????z";
              break;
            case 5:
              $traduction = $traduction. " olmayacaks??n??z";
              break;
            case 6:
              $traduction = $traduction. " olmayacaklar";
              break;
          }
          break;
        case 3:
          $traduction = $mot. " olacak";
          switch($personne){
            case 1:
              $traduction = $traduction." m??y??m ?";
              break;
            case 2:
              $traduction = $traduction. " m??s??n ?";
              break;
            case 3:
              $traduction = $traduction. " m?? ?";
              break;
            case 4:
              $traduction = $traduction. " m??y??z ?";
              break;
            case 5:
              $traduction = $traduction. " m??s??n??z ?";
              break;
            case 6:
              $traduction = $traduction. "lar m?? ?";
              break;
          }
          break;
      }
    break;
  }
  return $traduction;
  }

  function phrasefrancaise($motObjet,$temps,$type,$personne){
  
    //temps = 0present -1passe -2futur 
    //type = 1affirm - 2neg - 3question;
    //personne = 1;
    $mot = $motObjet->getFrancais();
    $adjectif = $motObjet->getAdjectif();
    $fin = " ";
    if($adjectif==true){
        $fin="s";
    }
    switch ($temps) {
      // pass??
      case 1 :
        switch($type){
          // passe affirm
          case 1:
            
            switch($personne){
              case 1:
                $traduction = "j'??tais ".$mot;
                break;
              case 2:
                $traduction = "tu ??tais ".$mot;
                break;
              case 3:
                $traduction = "il etait ".$mot;
                break;
              case 4:
                $traduction = "nous ??tions ".$mot.$fin;
                break;
              case 5:
                $traduction = "vous ??tiez ".$mot.$fin;
                break;
              case 6:
                $traduction = "ils ??taient ".$mot.$fin;
                break;
            }
            break;
          // passe neg
          case 2:
            switch($personne){
                case 1:
                    $traduction = "je n'??tais pas ".$mot;
                    break;
                  case 2:
                    $traduction = "tu n'??tais pas ".$mot;
                    break;
                  case 3:
                    $traduction = "il n'??tait pas ".$mot;
                    break;
                  case 4:
                    $traduction = "nous n'??tions pas ".$mot.$fin;
                    break;
                  case 5:
                    $traduction = "vous n'??tiez pas ".$mot.$fin;
                    break;
                  case 6:
                    $traduction = "ils n'??taient pas ".$mot.$fin;
                    break;
            }
            break;
          // passe quest
          case 3:
            switch($personne){
                case 1:
                    $traduction = "??tais je  ".$mot."?";
                    break;
                  case 2:
                    $traduction = "??tais tu  ".$mot."?";
                    break;
                  case 3:
                    $traduction = "??tait il ".$mot."?";
                    break;
                  case 4:
                    $traduction = "??tions nous ".$mot.$fin."?";
                    break;
                  case 5:
                    $traduction = "??tiez vous ".$mot.$fin."?";
                    break;
                  case 6:
                    $traduction = "??taient ils ".$mot.$fin."?";
                    break;
            }
    
            break;
        }
      break;
    
      // pr??sent 
      case 0 :
        switch($type){
          // present affirm
          case 1:
            switch($personne){
                case 1:
                    $traduction = "je suis  ".$mot;
                    break;
                  case 2:
                    $traduction = "tu es  ".$mot;
                    break;
                  case 3:
                    $traduction = "il est ".$mot;
                    break;
                  case 4:
                    $traduction = "nous sommes ".$mot.$fin;
                    break;
                  case 5:
                    $traduction = "vous ??tes ".$mot.$fin;
                    break;
                  case 6:
                    $traduction = "ils sont ".$mot.$fin;
                    break;
            }
            break;
          // present nega
          case 2:
            
            switch($personne){
                case 1:
                    $traduction = "je ne suis pas  ".$mot;
                    break;
                  case 2:
                    $traduction = "tu n'es pas  ".$mot;
                    break;
                  case 3:
                    $traduction = "il n'est pas ".$mot;
                    break;
                  case 4:
                    $traduction = "nous ne sommes pas ".$mot.$fin;
                    break;
                  case 5:
                    $traduction = "vous n'etes pas ".$mot.$fin;
                    break;
                  case 6:
                    $traduction = "ils ne sont pas ".$mot.$fin;
                    break;
            }
            break;
          
          //present question
          case 3:
            switch($personne){
                case 1:
                    $traduction = "suis je  ".$mot."?";
                    break;
                  case 2:
                    $traduction = "es tu  ".$mot."?";
                    break;
                  case 3:
                    $traduction = "est il ".$mot."?";
                    break;
                  case 4:
                    $traduction = "sommes nous ".$mot.$fin."?";
                    break;
                  case 5:
                    $traduction = "??tes vous ".$mot.$fin."?";
                    break;
                  case 6:
                    $traduction = "sont ils ".$mot.$fin."?";
                    break;
            }
            break;
        }
      break;
    
      //futur
      case 2 :
        switch($type){
          //futur affirm
          case 1:
            switch($personne){
                case 1:
                    $traduction = "je serai  ".$mot;
                    break;
                  case 2:
                    $traduction = "tu seras   ".$mot;
                    break;
                  case 3:
                    $traduction = "il sera ".$mot;
                    break;
                  case 4:
                    $traduction = "nous serons ".$mot.$fin;
                    break;
                  case 5:
                    $traduction = "vous serez ".$mot.$fin;
                    break;
                  case 6:
                    $traduction = "ils seront ".$mot.$fin;
                    break;
            }
            break;
          //futur negation
          case 2:
            switch($personne){
                case 1:
                    $traduction = "je ne serai pas  ".$mot;
                    break;
                  case 2:
                    $traduction = "tu ne seras pas   ".$mot;
                    break;
                  case 3:
                    $traduction = "il ne sera pas ".$mot;
                    break;
                  case 4:
                    $traduction = "nous ne serons pas ".$mot.$fin;
                    break;
                  case 5:
                    $traduction = "vous ne serez pas ".$mot.$fin;
                    break;
                  case 6:
                    $traduction = "ils ne seront pas ".$mot.$fin;
                    break;
            }
            break;
          case 3:
            switch($personne){
                case 1:
                    $traduction = "serai je  ".$mot."?";
                    break;
                  case 2:
                    $traduction = "seras tu   ".$mot."?";
                    break;
                  case 3:
                    $traduction = "sera-t-il  ".$mot."?";
                    break;
                  case 4:
                    $traduction = "serons nous ".$mot.$fin."?";
                    break;
                  case 5:
                    $traduction = "serez vous ".$mot.$fin."?";
                    break;
                  case 6:
                    $traduction = "seront ils ".$mot.$fin."?";
                    break;
              
            }
            break;
        }
      break;
    }
    return $traduction;
    }

    /**
     * @Route("synthese-partie/{partie}/supprjoueur/{id}",name="supprjoueur")
     * 
     */
    public function supprjoueur(int $partie,int $id){
      $joueur = $this->getDoctrine()
      ->getRepository(Joueurs::class)
      ->find($id);
      $partieObject = $this->getDoctrine()
      ->getRepository(Partie::class)
      ->find($partie);
      $nbre = $partieObject->getNbrjoueurs();
      $partieObject->setNbrjoueurs($nbre-1);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($joueur);
      $entityManager->flush();
      return $this->redirectToRoute('synthese-partie',['idpartie'=>$partie]); 
  }
    
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PdoGsb;
use MyDate;
use Illuminate\Support\Facades\Log;

class etatFraisController extends Controller
{
    function selectionnerMois()
    {
        if (session('visiteur') != null) {
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];
            $lesMois = PdoGsb::getLesMoisDisponibles($idVisiteur);
            // Afin de sélectionner par défaut le dernier mois dans la zone de liste
            // on demande toutes les clés, et on prend la première,
            // les mois étant triés décroissants
            $lesCles = array_keys($lesMois);
            $moisASelectionner = $lesCles[0];
            return view('listemois')
                ->with('lesMois', $lesMois)
                ->with('leMois', $moisASelectionner)
                ->with('visiteur', $visiteur);
        } else if (session('comptable') != null) {
            $visiteur = session('comptable');
            $gestionnaire = session('comptable');
            $idVisiteur = $visiteur['id'];
            $lesMois = PdoGsb::getLesMoisDisponibles($idVisiteur);
            // Afin de sélectionner par défaut le dernier mois dans la zone de liste
            // on demande toutes les clés, et on prend la première,
            // les mois étant triés décroissants
            $lesCles = array_keys($lesMois);
            //$moisASelectionner = $lesCles[0];//
            return view('listemois')
                ->with('lesMois', $lesMois)
                //->with('leMois', $moisASelectionner)//
                ->with('leMois', $lesCles)
                ->with('visiteur', $visiteur)
                ->with('visiteur', $visiteur);
        } else {
            return view('connexion')->with('erreurs', null);
        }

    }

    function voirFrais(Request $request)
    {
        if (session('visiteur') != null) {
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];
            $leMois = $request['lstMois'];
            $lesMois = PdoGsb::getLesMoisDisponibles($idVisiteur);
            $lesFraisForfait = PdoGsb::getLesFraisForfait($idVisiteur, $leMois);
            $lesInfosFicheFrais = PdoGsb::getLesInfosFicheFrais($idVisiteur, $leMois);
            $numAnnee = MyDate::extraireAnnee($leMois);
            $numMois = MyDate::extraireMois($leMois);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModifFr = MyDate::getFormatFrançais($dateModif);
            $vue = view('listefrais')->with('lesMois', $lesMois)
                ->with('leMois', $leMois)->with('numAnnee', $numAnnee)
                ->with('numMois', $numMois)->with('libEtat', $libEtat)
                ->with('montantValide', $montantValide)
                ->with('nbJustificatifs', $nbJustificatifs)
                ->with('dateModif', $dateModifFr)
                ->with('lesFraisForfait', $lesFraisForfait)
                ->with('visiteur', $visiteur);
            return $vue;
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function test()
    {
        if (session('visiteur') != null) {
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];
            return view('test')->with('visiteur', $visiteur);
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function listePersonne()
    {
        //cette fonction permet d'afficher la liste des visiteurs inscrits dans la BDD
        if (session('gestionnaire') != null) {
            $gestionnaire = session('gestionnaire');
            $visiteur = session('gestionnaire');

            $liste = Pdogsb::Listepersonne();
            return view('listepersonne')->with('liste', $liste)
                ->with('gestionnaire', $gestionnaire)
                ->with('visiteur', $visiteur);;
        }
    }

    function suppruser(Request $request)
    {
        //cette fonction va permettre de supprimer un utilisateur de la BDD
        if (session('gestionnaire') != null) {
            $gestionnaire = session('gestionnaire');
            $visiteur = session('gestionnaire');
            $id = htmlentities($request['id']);
            $req1 = Pdogsb::supressionligne($id);
            $req2 = Pdogsb::supprimerfiche($id);
            $req = Pdogsb::supprimerUser($id);
            $liste = Pdogsb::listePersonne();
            return view('listepersonne')->with('liste', $liste)->with('gestionnaire', $gestionnaire)
                ->with('visiteur', $visiteur);
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function selectionneruser(Request $request)
    {
        //cette fonction permet de trouver un utilisateur dans la BDD grâce à son id
        //elle va permettre pour une inscription de vérifier si un utilisateur
        //avec le même id existe
        if (session('visiteur') != null) {

            $visiteur = session('visiteur');

            $id = htmlentities($request['id']);
            //dd($id);
            $liste = Pdogsb::selectionneruser($id);
            return view('formmodif')->with('liste', $liste)
                ->with('visiteur', $visiteur);
        } else if (session('gestionnaire') != null) {
            $gestionnaire = session('gestionnaire');
            $visiteur = session('gestionnaire');
            $id = htmlentities($request['id']);
            //dd($id);
            $liste = Pdogsb::selectionneruser($id);
            return view('formmodif')->with('liste', $liste)
                ->with('gestionnaire', $gestionnaire)
                ->with('visiteur', $visiteur);
        } else if (session('comptable') != null) {
            $comptable = session('comptable');
            $visiteur = session('comptable');
            $id = htmlentities($request['id']);
            //dd($id);
            $liste = Pdogsb::selectionneruser($id);
            return view('formmodif')->with('liste', $liste)
                ->with('comptable', $comptable)
                ->with('visiteur', $visiteur);
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function ajouterUtilisateur(Request $request)
    {

        if (session('gestionnaire') != null) {
            //si le visiteur est déja crée, l'utilisateur va être renvoyé au formulaire d'ajout
            //return view('form_ajout')->with('visiteur',$visiteur);//
            $gestionnaire = session('gestionnaire');
            $visiteur = session('gestionnaire');
            //dd($visiteur);
            $lettres = range('a', 'z'); // Crée un tableau contenant les lettres de 'a' à 'z'
            $lettreAleatoire = $lettres[array_rand($lettres)];
            $nombreAleatoire = strval(rand(0, 1000)); // Sélectionne une lettre aléatoire du tableau
            $id = $lettreAleatoire . $nombreAleatoire;
            $login = htmlentities($request['login']);
            $mdp = htmlentities($request['mdp']);
            $nom = htmlentities($request['nom']);
            $prenom = htmlentities($request['prenom']);
            $adresse = htmlentities($request['adresse']);
            $ville = htmlentities($request['ville']);
            $cp = htmlentities($request['cp']);
            $date = htmlentities($request['date']);
            $test = Pdogsb::selectionneruser($id);
            if (empty($test)) {
                //verification de si l'utilisateur existe
                //s'il est pas existant on peut donc le créer
                $req = Pdogsb::ajouter($id, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville, $date);
                $liste = Pdogsb::listePersonne();
                return view('listepersonne')->with('visiteur', $visiteur)->with('liste', $liste)->with('gestionnaire', $gestionnaire);
            }

        } else {
            return view('connexion')->with('erreurs', null);
        }
    }


    function modifierUser(Request $request)
    {
        if (session('gestionnaire') != null) {
            //fonction qui récup les valeurs du formulaire et modifie en conséquence le visiteur
            $gestionnaire = session('gestionnaire');
            $visiteur = session('gestionnaire');
            $id = htmlentities($request['id']);
            $login = htmlentities($request['login']);
            $mdp = htmlentities($request['mdp']);
            $nom = htmlentities($request['nom']);
            $prenom = htmlentities($request['prenom']);
            $adresse = htmlentities($request['adresse']);
            $ville = htmlentities($request['ville']);
            $cp = htmlentities($request['cp']);
            $date = $request['date'];
            $req = Pdogsb::modifierUser($id, $nom, $prenom, $login, $adresse, $cp, $ville, $date, $mdp);
            $liste = Pdogsb::Listepersonne();
            return view('listepersonne')->with('visiteur', $visiteur)->with('liste', $liste)->with('gestionnaire', $gestionnaire);
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function ajoutUser()
    {
        //cette fonction fait le lien entre la route et la vue du formulaire d'ajout

        if (session('gestionnaire') != null) {
            $gestionnaire = session('gestionnaire');
            $visiteur = session('gestionnaire');
            return view('form_ajout')->with('gestionnaire', $gestionnaire)->with('visiteur', $visiteur);
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function listeInvalide()
    {
        if (session('gestionnaire') != null) {
            $gestionnaire = session('gestionnaire');
            $visiteur = session('gestionnaire');
            $liste = PdoGsb::listeInvalide();

            return view('listeFraisPasValide')->with('gestionnaire', $gestionnaire)->with('visiteur', $visiteur)
                ->with('liste', $liste);

        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function listeNom()
    {
        if (session('comptable') != null) {
            $gestionnaire = session('comptable');
            $visiteur = session('comptable');
            $liste = PdoGsb::listePersonne();
            return view('listeFraisPasValide')->with('comptable', $gestionnaire)->with('visiteur', $visiteur)
                ->with('liste', $liste);
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function listeFrais()
    {
        if (session('comptable') != null) {
            $comptable = session('comptable');
            $visiteur = session('comptable');
            $listeFrais = PdoGsb::listeValide();
            $lesMois=PdoGsb::lesMois();
            dd($listeFrais);
            return view('listeFraiss')->with('comptable', $comptable)->with('visiteur', $visiteur)
                ->with('liste', $listeFrais)

                ->with('lesMois',$lesMois);
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function genererEtat(Request $request){

        $id = $request['id'];
    
        $unVisiteur = PdoGsb::selectionneruser($id);
    
        // Używamy pierwszego indeksu [0], ponieważ zakładamy, że mamy jeden wynik
        $visiteurData = $unVisiteur[0];  
    
        $pdf = PDF::LoadHTML(
            "<ul>
                <li>{$visiteurData['nom']}</li>
                <li>{$visiteurData['prenom']}</li>
                <li>{$visiteurData['id']}</li>
                <li>{$visiteurData['adresse']}</li>
                <li>{$visiteurData['cp']}</li>
                <li>{$visiteurData['ville']}</li>
                <li>{$visiteurData['dateEmbauche']}</li>
            </ul>"
        );
    
        return $pdf->download("bonjour.pdf");
    }
    
    
}



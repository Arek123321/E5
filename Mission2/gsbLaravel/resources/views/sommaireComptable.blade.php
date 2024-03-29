@extends ('modeles/visiteur')
@section('menu')
    <!-- Division pour le sommaire -->
    <div id="menuGauche">
        <div id="infosUtil">

        </div>
        <ul id="menuList">
            <li >
                <strong>Vous êtes comptable</strong>
                <strong>Bonjour {{ $comptable['nom'] . ' ' . $comptable['prenom'] }}</strong>

            </li>
            <li class="smenu">
                <a href="{{ route('validFrais')}}" title="Saisie fiche de frais ">Valider les frais</a>
            </li>
            <li class="smenu">
                <a href="{{ route('fraisliste') }}" title="Consultation de mes fiches de frais">Liste des frais valides</a>
            </li>

            <li class="smenu">
                <a href="{{ route('chemin_deconnexion') }}" title="Se déconnecter">Déconnexion</a>
            </li>


        </ul>

    </div>
@endsection

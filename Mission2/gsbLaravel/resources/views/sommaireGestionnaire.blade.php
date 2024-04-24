@extends ('modeles/visiteur')
@section('menu')
    <!-- Division pour le sommaire -->
    <div id="menuGauche">
        <div id="infosUtil">

        </div>
        <ul id="menuList">
            <li >
                <strong>Vous êtes gestionnaire</strong>
                <strong>Bonjour {{ $gestionnaire['nom'] . ' ' . $gestionnaire['prenom'] }}</strong>

            </li>
           
            <li class="smenu">
                <a href="{{ route('chemin_deconnexion') }}" title="Se déconnecter">Déconnexion</a>
            </li>
            <li class="smenu">
                <a href="{{ route('listepersonne') }}" title="Se déconnecter">Liste des utilisateurs</a>
            </li>
        </ul>

    </div>
@endsection

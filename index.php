<?php
    ob_start();
    session_start();

    require __DIR__."/vendor/autoload.php";

    if(empty($_SESSION['userLogin'])) {
        echo "<h1>Guest User</h1>";

        /**
         * AUTH GOOGLE
         */

         $google = new League\OAuth2\Client\Provider\Google(GOOGLE);
         $authUrl = $google->getAuthorizationUrl();

         $error = filter_input(INPUT_GET, "erro", FILTER_SANITIZE_STRING);
         $code = filter_input(INPUT_GET, "code", FILTER_SANITIZE_STRING);

         

        if($error) {
            echo "<h1>Você precisa se autorizar para continuar!</h1>";
        }

        if($code) {
            $token = $google->getAccessToken("authorization_code", [
                "code" => $code
            ]);

        $_SESSION['userLogin'] = serialize($google->getResourceOwner($token));
        header("location: ".GOOGLE["redirectUri"]);
        exit;

        }

        echo "<a title='login Goolge' href='{$authUrl}'>Google Login</a>";
    } else {
        echo "<h1>User</h1>";

        $user = unserialize($_SESSION['userLogin']);

        echo "<img whidt='120' src='{$user->getAvatar()}' alt='{$user->getFirstName()}' title='{$user->getFirstName()}' /><h1>Bem Vindo(a) {$user->getFirstName()}";
        echo "<br>";
        var_dump($user->toArray());
        
        echo "<a href='?off=true' title='Sair'>Sair</a>";
        $off = filter_input(INPUT_GET, "off", FILTER_VALIDATE_BOOLEAN);

        if($off) {
            unset($_SESSION['userLogin']);

            header("location: ".GOOGLE["redirectUri"]);
        }
    }

    ob_end_flush();
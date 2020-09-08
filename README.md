# SymfonyWithApi

## Installer la barre debug :
- composer require --dev symfony/profiler-pack

## Installer lexik jwt pour le token:
- "ext-http": "*", à ajouter au composer.json dans require, faire 
  - sudo apt install php-pecl-http en cas de pb
  - composer upgrade
- composer require "lexik/jwt-authentication-bundle"
- mkdir -p config/jwt
Dans l'étape suivante bien recopier le passphrase créer dans le .env dans l'espace lexik
- openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
- openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout 
#### Ici on céer : config/packages/lexik_jwt_authentication.yaml, le directory jwt et les fichiers private.pem et public.pem 
- Pour la suite voir ressource car il y à la config à revoir pour le .env, config/packages/lexik_jwt_authentication.yaml, config/packages/security.yaml, config/routes.yaml.
#### Côté Front on doit supprimer le fichier config/jwt/private.pem
#### Et on doit recopier les données config/jwt/public.pem de l'Api et le coller sur celui du front pour avoir une signature identique du token ou sinon le token genere une erreur et l'authentification ne se fait pas

- Et recopier coller (token extractor au même niveau que public_key), voir dans config/packages/lexik_jwt_authentification.yaml : 
    - token_extractors:
        - (#) look for a token as Authorization Header
        - authorization_header:
            - enabled: true
            - prefix:  Bearer
            - name:    Authorization
        - (#) check token in a cookie
        - cookie:
            - enabled: true
            - name:    BEARER
## Changer la config/packages/security.yaml en métant à jour :
- providers:
        jwt:
            lexik_jwt: ~
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            stateless: true
            anonymous: true
            provider: jwt
            guard:
                authenticators:
                    - lexik_jwt_authentication.jwt_token_authenticator "
                    
##### Si le token se rajoute tout seul dans les requête à l'Api rien à faire, si il y à des erreurs rajouté le token en paramètre (regarder l'exemple suivant ou chercher dans les controller) :
- $httpClient->request('PUT', 'http://127.0.0.1:8001/api/users/' . $id,
        [
        'headers' => [
            'Authorization' => 'Bearer '.$token,
        ],

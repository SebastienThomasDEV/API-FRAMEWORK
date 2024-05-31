<?php

namespace Sthom\Back\Controller;

use Sthom\Back\Entity\User;
use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Services\PasswordHasher;
use Sthom\Back\Kernel\Framework\Services\Request;
use Sthom\Back\Repository\UserRepository;

class ExampleController extends AbstractController
{

    /**
     * Definition d'une route:
     *
     * Vous pouvez créer autant de méthodes que vous le souhaitez.
     * Chaque méthode doit être annotée avec #[Route] pour être lisible par notre routeur.
     * De plus, chaque méthode doit renvoyer un tableau associatif.
     * Ce tableau sera converti en JSON et renvoyé au client.
     *
     * Vous avez 3 paramètres à passer à #[Route]:
     *
     * - path: le chemin de la route
     * - requestType: le type de requête (GET, POST, PUT, DELETE)
     * - guarded: true si la route est protégée par un middleware, false sinon (@see JwtMiddleware)
     *
     * Voici un exemple de route:
     */
    #[Route(path: '/', requestType: 'GET', guarded: false)]
    public final function base(UserRepository $userRepository): array
    {

        return $this->send([
            'message' => 'Hello World',
        ]);
    }

    /**
     * Vous pouvez également utiliser d'autres types de requêtes.
     * Par exemple, POST:
     *
     * Pour intéragir avec la requête, vous pouvez injecter la classe Request dans votre méthode.
     * Cette classe est un service qui vous permet d'accéder aux données de la requête (@see Request
     * Pour les requêtes POST, vous pouvez accéder aux données envoyées par le client via la méthode getBody() de la requête.
     * Vous pouvez également renvoyer des données au client.
     *
     */
    #[Route(path: '/test', requestType: 'get', guarded: false)]
    public final function post(Request $request): array
    {
        $body = $request->getBody();
        return $this->send([
            'message' => 'Hello ' . $body['name'],
        ]);
    }

    /**
     * Vous pouvez également utiliser des paramètres dans vos routes.
     * Par exemple, vous pouvez passer un nom dans l'URL et le récupérer dans votre méthode.
     *
     * Pour récupérer un paramètre, vous pouvez utiliser la méthode getAttribute de la requête.
     * Le service Request posséde plusieurs méthodes pour accéder aux données de la requête.
     *
     * listes des méthodes:
     *
     * - getAttribute(string $name): récupère un paramètre de la route
     * - getAttributes(): récupère tous les paramètres de la route
     * - getBody(): récupère les données envoyées par le client
     * - getFiles(): récupère les fichiers envoyés par le client
     * - getHeaders(): récupère les en-têtes de la requête
     * - getMethod(): récupère la méthode HTTP de la requête
     * - getPath(): récupère le chemin de la requête
     *
     */
    #[Route(path: '/arg/{name}', requestType: 'GET', guarded: false)]
    public final function arg(Request $request): array
    {
        $name = $request->getAttribute('name');
        return $this->send([
            'message' => 'Hello ' . $name,
        ]);
    }



    /**
     * Pour protéger une route, vous pouvez définir le paramètre guarded à true.
     * Cela signifie que la route est protégée par un middleware.
     * Ce middleware vérifie la validité du JWT envoyé par le client.
     * Ce middleware doit être configuré dans le fichier .env.
     *
     * Avec les variables d'environnement JWT_PRIVATE_KEY_PATH et JWT_PUBLIC_KEY_PATH
     * qui sont les chemins vers les clés privée et publique du JWT.
     *
     * En enfin les variables JWT_USER_TABLE et JWT_USER_IDENTIFIER
     * qui sont le nom de la table utilisateur et l'identifiant de l'utilisateur.
     *
     * Il ira automatiquement chercher l'utilisateur dans la base de données.
     * et renverra une erreur si l'utilisateur n'est pas trouvé.
     */
    #[Route(path: '/guarded', requestType: 'GET', guarded: true)]
    public final function guarded(Request $request): array
    {
        return $this->send([
            'message' => 'Cette route est protégée',
        ]);
    }



    /**
     *
     * Les contrôleurs peuvent également interagir avec les services.
     * Par exemple, vous pouvez injecter un service de repository dans votre méthode.
     * Ce service vous permet d'interagir avec la base de données.
     * Vous pouvez appeler des méthodes pour récupérer des données de la base de données.
     *
     * ! Info, les repositories vous renvoient des objets du type de l'entité associée.
     *
     * la méthode send() convertira ces objets en tableau associatif pour les renvoyer au client.
     *
     * liste des méthodes du service UserRepository:
     * - find(int $id): récupère un utilisateur par son identifiant
     * - findAll(): récupère tous les utilisateurs
     * - findBy(array $criteria): récupère un utilisateur par des critères
     * - save(array $data): sauvegarde un utilisateur sert à créer et mettre à jour un utilisateur
     * - delete(int $id): supprime un utilisateur
     *
     */
    #[Route(path: '/repository', requestType: 'GET', guarded: false)]
    public final function getAll(UserRepository $userRepository): array
    {
        $users = $userRepository->findAll();

        return $this->send([
            'users' => $users,
        ]);
    }


    /**
     * Vous pouvez également injecter la requête et autant de services (disponible dans le dossier /Services) que vous le souhaitez.
     * Par exemple, vous pouvez injecter un service de hachage de mot de passe.
     * Ce service vous permet de hacher et de vérifier les mots de passe.
     *
     * Dans cette exemple, nous créons un utilisateur et hachons son mot de passe avant de le sauvegarder.
     * Premièrement, nous récupérons les données de la requête via la méthode getBody() qui récupére le corps de la requête.
     * Ensuite, nous créons un nouvel utilisateur et définissons ses propriétés.
     * Nous hachons le mot de passe avec le service de hachage de mot de passe.
     * Enfin, nous sauvegardons l'utilisateur avec le service de repository.
     *
     *
     */
    #[Route(path: '/create', requestType: 'POST', guarded: false)]
    public final function get(UserRepository $userRepository, Request $request, PasswordHasher $hasher): array
    {
        $body = $request->getBody();
        $user = new User();
        $user->setEmail($body['email']);
        $user->setMdp($hasher->hash($body['mdp']));
        $user->setRoles('ROLE_USER');
        $user->setNom($body['nom']);
        $user->setPrenom($body['prenom']);
        $userRepository->save($user);
        return $this->send([
            'message' => "L'utilisateur a été créé",
        ]);
    }


    /**
     *
     * Grâce au service Request, vous pouvez récupérer les paramètres de la requête.
     * Vous pouvez récupérer un paramètre de la requête en utilisant la méthode getArg() du service Request.
     * Vous pouvez également chainé les paramètres de la requête.
     *
     * @example /update/{id}/{name}
     *
     * Vous pouvez également mettre à jour un utilisateur.
     * Pour cela, vous devez récupérer l'utilisateur à mettre à jour.
     * Vous pouvez le faire en utilisant la méthode find() du service UserRepository.
     * Ensuite, vous pouvez modifier les propriétés de l'objet utilisateur.
     * Enfin, vous pouvez sauvegarder l'utilisateur avec le service UserRepository.
     *
     *
     */
    #[Route(path: '/update/{id}', requestType: 'GET', guarded: true)]
    public final function update(UserRepository $userRepository, Request $request): array
    {
        $id = $request->getAttribute('id');
        $user = $userRepository->find($id);
        $user->setNom($request->getAttribute('name'));
        $userRepository->save($user);
        return $this->send([
            'message' => "L'utilisateur a été mis à jour",
        ]);
    }








}
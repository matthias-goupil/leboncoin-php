<?php

namespace Config;

use PHPMailer\PHPMailer\PHPMailer;
use TheFeed\Business\Services\PDFService;
use TheFeed\Business\Services\MailService;
use Framework\Services\ServerSessionManager;
use Symfony\Component\DependencyInjection\Reference;
use TheFeed\Application\AnnouncementController;
use TheFeed\Application\API\PublicationControllerAPI;
use TheFeed\Application\API\UtilisateurControllerAPI;
use TheFeed\Application\CategoryController;
use TheFeed\Application\PublicationController;
use TheFeed\Application\UserController;
use TheFeed\Application\UtilisateurController;
use TheFeed\Business\Entity\Announcement;
use TheFeed\Business\Entity\Category;
use TheFeed\Business\Entity\Publication;
use TheFeed\Business\Entity\User;
use TheFeed\Business\Entity\Utilisateur;
use TheFeed\Business\Services\AnnouncementService;
use TheFeed\Business\Services\PublicationService;
use TheFeed\Business\Services\UserService;
use TheFeed\Business\Services\UtilisateurService;
use TheFeed\Listener\AppListener;
use TheFeed\Storage\SQL\doctrine\AnnouncementRepository;
use TheFeed\Storage\SQL\doctrine\CategoryRepository;
use TheFeed\Storage\SQL\doctrine\UserRepository;
use TheFeed\Storage\SQL\PublicationRepositorySQL;
use TheFeed\Storage\SQL\UtilisateurRepositorySQL;

class ConfigurationGlobal
{
    const debug = false;

    const appRoot = __DIR__ . '/../src';

    const parameters = [
        "profile_pictures_storage" => __DIR__ . '/../web/assets/img/utilisateurs',
        "secret_seed" => "qh7878qfsfsr_ttezo!"
    ];

    const views = "View";

    const repositories = [
        Publication::class => PublicationRepositorySQL::class,
        Utilisateur::class => UtilisateurRepositorySQL::class,
        User::class => UserRepository::class,
        Announcement::class => AnnouncementRepository::class,
        Category::class => CategoryRepository::class
    ];

    const userSessionManager = [
        "manager" => ServerSessionManager::class,
        "parameters" => [
            'environment' => '%environment%'
        ]
    ];

    const controllers = [
        "user_controller" => UserController::class,
        "category_controller" => CategoryController::class,
        "announcement_controller" => AnnouncementController::class,
        "publication_controller" => PublicationController::class,
        "utilisateur_controller" => UtilisateurController::class,
        "publication_controller_api" => PublicationControllerAPI::class,
        "utilisateur_controller_api" => UtilisateurControllerAPI::class,
    ];

    const routes = [
        "profil_user" => [
            "path" => "/user/{userId}",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "user_controller::profil",
//                "_logged" => true,
            ]
        ],
        "liked_announcements" => [
            "path" => "/user/{userId}/liked",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "user_controller::annoucementsLiked",
//                "_logged" => true,
            ]
        ],
        "logout_user" => [
            "path" => "/logout",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "user_controller::logout",
                "_logged" => true,
            ]
        ],
        "announcements_list" => [
            "path" => "/",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "announcement_controller::list",
            ]
        ],
        "register_user" => [
            "path" => "/register",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "user_controller::register",
            ]
        ],

        "submit_register_user" => [
            "path" => "/register",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "user_controller::submitRegister",
            ]
        ],
        "login_user" => [
            "path" => "/login",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "user_controller::login",
            ]
        ],
        "submit_login_user" => [
            "path" => "/login",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "user_controller::submitLogin",
            ]
        ],
//        "feed" => [
//            "path" => "/",
//            "methods" => ["GET"],
//            "parameters" => [
//                "_controller" => "publication_controller::feed",
//            ]
//        ],
        "test" => [
            "path" => "/test",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "publication_controller::test",
            ]
        ],
        "feedPDF" => [
            "path" => "/pdf",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "publication_controller::feedPDF",
            ]
        ],
        "submit_feedy" => [
            "path" => "/feedy",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "publication_controller::submitFeedy",
                "_logged" => true,
            ]
        ],
        "connexion" => [
            "path" => "/connexion",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::getConnexion",
                "_force_not_logged" => true,
            ]
        ],
        "deconnexion" => [
            "path" => "/deconnexion",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::deconnexion",
                "_logged" => true,
            ]
        ],
        "inscription" => [
            "path" => "/inscription",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::getInscription",
                "_force_not_logged" => true,
            ]
        ],
        "page_perso" => [
            "path" => "/utilisateurs/page/{idUser}",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "utilisateur_controller::pagePerso",
                "idUser" => null,
            ]
        ],
        "submit_inscription" => [
            "path" => "/inscription",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "utilisateur_controller::submitInscription",
                "_force_not_logged" => true,
            ]
        ],
        "submit_connexion" => [
            "path" => "/connexion",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "utilisateur_controller::submitConnexion",
                "_force_not_logged" => true,
            ]
        ],
        "submit_feedy_api" => [
            "path" => "/api/feedy",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "publication_controller_api::submitFeedy",
                "_logged" => true,
            ]
        ],
        "remove_feedy_api" => [
            "path" => "api/feedy/{idPublication}",
            "methods" => ["DELETE"],
            "parameters" => [
                "_controller" => "publication_controller_api::removeFeedy",
                "idPublication" => null,
                "_logged" => true,
            ]
        ],
        "remove_utilisateur_api" => [
            "path" => "api/utilisateur/{idUser}",
            "methods" => ["DELETE"],
            "parameters" => [
                "_controller" => "utilisateur_controller_api::removeUtilisateur",
                "idUser" => null,
                "_logged" => true,
            ]
        ],
    ];

    const listeners = [
      "app_listener"
    ];

    public static function services($container): void
    {
        $container->register('user_service', UserService::class)
            ->setArguments([
                new Reference('repository_manager'),
                new Reference('session_manager'),
                "%secret_seed%",
                "%profile_pictures_storage%"
            ])
        ;

        $container->register('announcement_service', AnnouncementService::class)
            ->setArguments([
                new Reference('repository_manager'),
                new Reference('user_service'),
            ])
        ;

        $container->register('publication_service', PublicationService::class)
            ->setArguments([
                new Reference('repository_manager'),
                new Reference('utilisateur_service'),
            ])
        ;
        $container->register('utilisateur_service', UtilisateurService::class)
            ->setArguments([
                new Reference('repository_manager'),
                new Reference('session_manager'),
                "%secret_seed%",
                "%profile_pictures_storage%"
            ])
        ;
        $container->register('mail_service', MailService::class)
            ->setArguments([
                               "Steffan@lemauvaiscoin.com",
                               "Steffan LeMauvaisCoin"
           ])
        ;
        $container->register('pdf_generator', PDFService::class);
        $container->register('app_listener', AppListener::class)
            ->setArguments([
                new Reference('utilisateur_service'),
                new Reference('twig'),
                new Reference('url_generator')]);
    }
}
<?php

namespace Config;

use PHPMailer\PHPMailer\PHPMailer;
use TheFeed\Business\Services\CategoryService;
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
        "profile_pictures_storage" => __DIR__ . '/../web/assets/img/annonces',
        "secret_seed" => "qh7878qfsfsr_ttezo!"
    ];

    const views = "View";

    const repositories = [
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
    ];

    const routes = [
        "announcement_pdf" => [
            "path" => "/announcement/{idAnnouncement}/pdf",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "announcement_controller::pdf",
            ]
        ],
        "search_announcements" => [
            "path" => "/search",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "announcement_controller::search",
            ]
        ],
        "add_liked_announcements" => [
            "path" => "/announcement/{idAnnouncement}/addLike",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "announcement_controller::addToFavorite",
                "_logged" => true,
            ]
        ],
        "remove_liked_announcements" => [
            "path" => "/announcement/{idAnnouncement}/removeLike",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "announcement_controller::removeFromFavorite",
                "_logged" => true,
            ]
        ],
        "delete_announcement" => [
            "path" => "/announcement/{idAnnouncement}/delete",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "announcement_controller::delete",
                "_logged" => true,
            ]
        ],

        "create_announcement" => [
            "path" => "/announcement/create",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "announcement_controller::create",
                "_logged" => true,
            ]
        ],

        "submit_create_announcement" => [
            "path" => "/announcement/create",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "announcement_controller::submitCreate",
                "_logged" => true,
            ]
        ],

        "update_announcement" => [
            "path" => "/announcement/{idAnnouncement}/update",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "announcement_controller::update",
                "_logged" => true,
            ]
        ],

        "submit_update_announcement" => [
            "path" => "/announcement/{idAnnouncement}/update",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "announcement_controller::submitUpdate",
                "_logged" => true,
            ]
        ],
        "show_announcement" => [
            "path" => "/announcement/{idAnnouncement}",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "announcement_controller::show",
            ]
        ],


        "liked_announcements" => [
            "path" => "/user/liked",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "user_controller::annoucementsLiked",
                "_logged" => true,
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
        "update_user" => [
            "path" => "/user/update",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "user_controller::update",
                "_logged" => true,
            ]
        ],

        "submit_update_user" => [
            "path" => "/user/update",
            "methods" => ["POST"],
            "parameters" => [
                "_controller" => "user_controller::submitUpdate",
                "_logged" => true,
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
        "profil_user" => [
            "path" => "/user/profil",
            "methods" => ["GET"],
            "parameters" => [
                "_controller" => "user_controller::profil",
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
            ]);

        $container->register('announcement_service', AnnouncementService::class)
            ->setArguments([
                new Reference('repository_manager'),
                new Reference('user_service'),
                "%profile_pictures_storage%"
            ]);

        $container->register('category_service', CategoryService::class)
            ->setArguments([
                new Reference('repository_manager'),
                new Reference('user_service'),
            ]);
        $container->register('mail_service', MailService::class)
            ->setArguments([
                "Steffan@lemauvaiscoin.com",
                "Steffan LeMauvaisCoin"
            ]);
        $container->register('pdf_generator', PDFService::class);
        $container->register('app_listener', AppListener::class)
            ->setArguments([
                new Reference('user_service'),
                new Reference('twig'),
                new Reference('url_generator')]);
        $container->register('category_service', CategoryService::class)
            ->setArguments([
                new Reference('repository_manager'),
                new Reference('user_service'),
            ]);
    }
}
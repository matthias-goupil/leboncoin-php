<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use TheFeed\Business\Services\MailService;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\Business\Exception\ServiceException;

class UserController extends Controller
{

    public function register() {
        return $this->render("Users/register.html.twig");
    }

    public function submitRegister(Request $request) {
        $firstname = $request->get("firstname");
        $lastname = $request->get("lastname");
        $tel = $request->get("tel");
        $passwordClair = $request->get("password");
        $adresseMail = $request->get("email");
//        $profilePictureFile = $request->files->get("profilePicture");
        $userService = $this->container->get('user_service');
        $mailService = $this->container->get('mail_service');

        try {
            $userService->createUser($adresseMail,$firstname, $lastname, $passwordClair, $tel);

            // Envoi d'un email de confirmation
            $body = "Bonjour $firstname $lastname,<br>Votre inscription a bien été prise en compte.<br>Cordialement,<br>L'équipe LeMauvaisCoin";
            $to = $adresseMail;
            $subject = "Confirmation d'inscription";
            $attachments = [];
            $mailService->sendMail($to, $subject, $body, $attachments);

            $this->addFlash("success","Inscription réeussie!");
            return $this->redirectToRoute('feed');
        } catch (ServiceException $e) {
            $this->addFlash("error", $e->getMessage());
            return $this->render("Users/register.html.twig", ["firstname" => $firstname, "email" => $adresseMail, "lastname" => $lastname, "tel" => $tel]);
        }
    }

    public function login() {
        return $this->render("Users/login.html.twig");
    }

    public function submitLogin(Request $request) {
        $email = $request->get("email");
        $passwordClair = $request->get("password");
        $userService = $this->container->get('user_service');
        try {
            $userService->login($email, $passwordClair);
            return $this->redirectToRoute('feed');
        } catch (ServiceException $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->render('Users/login.html.twig',["email" => $email]);
        }
    }

    public function logout() {
        $userService = $this->container->get('user_service');
        $userService->logout();
        return $this->redirectToRoute('feed');
    }

    public function profil() {
        $userService = $this->container->get('user_service');
        if($userService->estConnecte()){
            $user = $userService->getUser($userService->getUserId());
            return $this->render("Users/profil.html.twig", ["user" => $user]);
        }
        return $this->redirectToRoute("login_user");
    }

    public function annoucementsLiked() {
        $userService = $this->container->get('user_service');
        if($userService->estConnecte()){
            $user = $userService->getUser($userService->getUserId());
            return $this->render("Users/liked.html.twig", [
                "liked" => $user->getLikedAnnouncements()
            ]);
        }
        return $this->redirectTo("login_user");
    }

    public function update() {
        $userService = $this->container->get('user_service');
        if($userService->estConnecte()){
            $user = $userService->getUser($userService->getUserId());
            return $this->render("Users/update.html.twig", ["user" => $user]);
        }
        return $this->redirectTo("list_announcement");
    }

    public function submitUpdate(Request $request) {
        $firstname = $request->get("firstname");
        $lastname = $request->get("lastname");
        $tel = $request->get("tel");
        $passwordClair = $request->get("password");
        $userService = $this->container->get('user_service');
        try {
            $userService->updateConnectedUser($firstname, $lastname, $passwordClair, $tel);
            $this->addFlash("success","Modification réussie!");
            echo "oui";

            return $this->redirectToRoute('profil_user');
        }
        catch (ServiceException $e) {
            $this->addFlash("error",$e->getMessage());
            return $this->render("Users/update.html.twig", ["user" => ["firstname" => $firstname, "email" => $adresseMail, "lastname" => $lastname, "tel" => $tel]]);
        }
    }
}
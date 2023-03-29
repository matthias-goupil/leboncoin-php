<?php

namespace TheFeed\Application;
use \Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TheFeed\Business\Entity\Category;
use TheFeed\Business\Entity\User;

class AnnouncementController extends Controller
{

    public function list() {
        $announcementService = $this->container->get('announcement_service');
        $announcements = $announcementService->getAll();
        $userService = $this->container->get('user_service');
        return $this->render("Announcements/list.html.twig", [
            "announcements" => $announcements,
            "liked" => [],
        ]);
    }

    public function search(Request $request) {
        $search = $request->get('search');
        $city = $request->get('city');
        $category = $request->get('category');
        return $this->render("Announcements/list.html.twig");
    }

    public function show($idAnnouncement) {
        $service = $this->container->get('announcement_service');
        $annoucement = $service->getAnnouncement($idAnnouncement);
        return $this->render("Announcements/show.html.twig", [
            "announcement" => $annoucement
        ]);
    }

    public function create() {
        $categoryService = $this->container->get('category_service');
        return $this->render("Announcements/create.html.twig",[
            "categories" => $categoryService->getCategories()
        ]);
    }

    public function submitCreate(Request $request) {
        $serviceA = $this->container->get('announcement_service');
        $serviceC = $this->container->get('category_service');
        $serviceU = $this->container->get('user_service');
        if($serviceU->estConnecte()) {
            $name = $request->get("name");
            $description = $request->get("description");
            $picture = $request->files->get("picture");
            $adress = $request->get("adress");
            $city = $request->get("city");
            $postalcode = $request->get("postalCode");
            $price = $request->get("price");
            $category = $serviceC->createCategoryIfDontExists($request->get("category"));
            $user = $serviceU->getUser($serviceU->getUserId());
            $announcement = $serviceA->createAnnouncement($name, $description, $picture, $adress, $city, $postalcode, $price, $category, $user);
            return $this->redirectToRoute("show_announcement", ["idAnnouncement" => $announcement->getId()]);
        }
        return $this->redirectToRoute("login_user");
    }

    public function update($idAnnouncement) {
        $service = $this->container->get('announcement_service');
        $announcement = $service->getAnnouncement($idAnnouncement);
        return $this->render("Announcements/update.html.twig", [
            "announcement" => $announcement
        ]);
    }

    public function submitUpdate($idAnnouncement,Request $request) {
        $serviceA = $this->container->get('announcement_service');
        $serviceC = $this->container->get('category_service');
        $serviceU = $this->container->get('user_service');

        if($serviceU->estConnecte()) {
            $name = $request->get("name");
            $description = $request->get("description");
            $picture = $request->files->get("picture");
            $adress = $request->get("adress");
            $city = $request->get("city");
            $postalcode = $request->get("postalCode");
            $price = $request->get("price");
            $category = $serviceC->createCategoryIfDontExists($request->get("category"));
            $user = $serviceU->getUser($serviceU->getUserId());

            $announcement = $serviceA->updateAnnouncement($idAnnouncement,$name, $description, $picture, $adress, $city, $postalcode, $price, $category, $user);

            return $this->redirectToRoute("show_announcement", ["idAnnouncement" => $announcement->getId()]);
        }
        return $this->redirectToRoute("login_user");
    }

    public function delete($idAnnouncement) {
        $service = $this->container->get('announcement_service');

        $service->delete($idAnnouncement);

        return $this->redirectToRoute("announcements_list");
    }

    public function addToFavorite($idAnnouncement) {
        $serviceA = $this->container->get('announcement_service');
        $serviceA->addToFavorite($idAnnouncement);
        return $this->redirectToRoute("liked_announcements");
    }

    public function removeFromFavorite($idAnnouncement) {

    }
}
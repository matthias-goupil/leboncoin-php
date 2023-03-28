<?php

namespace TheFeed\Application;
use \Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;

class AnnouncementController extends Controller
{

    public function list() {
        $announcementService = $this->container->get('announcement_service');
        $announcements = $announcementService->getAll();
        return $this->render("Announcements/list.html.twig", [
            "announcements" => $announcements
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

    }

    public function submitCreate() {
        $service = $this->container->get('announcement_service');

    }

    public function update() {
        $service = $this->container->get('announcement_service');

    }

    public function submitUpdate() {
        $service = $this->container->get('announcement_service');

    }

    public function delete() {
        $service = $this->container->get('announcement-service');
    }

    public function addToFavorite($idAnnouncement) {

    }

    public function removeFromFavorite($idAnnouncement) {

    }
}
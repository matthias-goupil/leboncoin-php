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
}
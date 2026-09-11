<?php
class HomeController
{
    public static function index(): void
    {
        $vehicles = VehicleModel::all();
        $categories = VehicleModel::categories();
        $services = ContentModel::services();
        $routes = ContentModel::routes();
        $tours = ContentModel::tours();
        $deals = ContentModel::deals();
        $whyUs = ContentModel::whyUs();
        $stats = ContentModel::stats();
        $tripTypes = ContentModel::tripTypes();
        $areas = ContentModel::areas();
        $faqs = ContentModel::faqs();
        $reviews = ContentModel::reviews();
        $reviewSummary = ContentModel::reviewSummary();
        $guides = ContentModel::guides();

        require __DIR__ . '/../views/home.php';
    }
}

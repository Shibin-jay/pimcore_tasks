<?php

namespace customConfigBundle\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends FrontendController
{
    /**
     * @Route("/custom_config")
     */
    public function indexAction(Request $request): Response
    {
        return new Response('Hello world from custom_config');
    }
    /**
     * @Route("/custom/save-data")
     */
    public function saveData(Request $request)
    {
        $data = json_decode($request->get('data'), true);
        if ($data === null) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid data']);
        }
        $configPath = $this->getParameter('kernel.project_dir') . '/bundles/customConfigBundle/config/systems.yaml';
        $yamlData = Yaml::dump($data, 4);
        file_put_contents($configPath, $yamlData);
        return new JsonResponse(['success' => true, 'message' => 'Data saved successfully']);
    }
    /**
     * @Route("/custom/retrieve-saved-data")
     */
    public function retrieveSavedData(Request $request)
    {
        // Retrieve the saved data from your storage (e.g., systems.yaml file)
        // Format the data as needed and return it as JSON
        // Example: Read data from the systems.yaml file and return it
        $configPath = $this->getParameter('kernel.project_dir') . '/bundles/customConfigBundle/config/systems.yaml';
        $yamlData = file_get_contents($configPath);
        $data = Yaml::parse($yamlData);

        return $this->json(['success' => true, 'data' => $data]);
    }
    /**
     * @Route("/custom/retrieve-data-auth")
     */
    public function testData(Request $request){
        $configPath = $this->getParameter('kernel.project_dir') . '/bundles/customConfigBundle/config/systems.yaml';
        $yamlData = file_get_contents($configPath);
        $data = Yaml::parse($yamlData);
        if ($data['checkboxValue'] === true){
            return $this->render("@customConfigBundle/test/test.html.twig",['item'=>$data]);
        }
        return  new Response('data is not allowed to show');
    }



}

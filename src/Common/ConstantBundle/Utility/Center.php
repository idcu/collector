<?php
namespace Common\ConstantBundle\Utility;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Center
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getServiceCode(){
        return $this->container->getParameter("service_code");
    }

    public function register($client)
    {
        $api_url = $this->container->getParameter("center_register_url");
        $buzz = $this->container->get('buzz');
        $url = $this->getUrlWithAuthorizeParameters($api_url);
        $result = json_decode($buzz->post($url,array(),json_encode((array)$client))->getContent());
        $this->statusCheck($result);
        return $result;
    }

    public function update($client)
    {
        $api_url = $this->container->getParameter("center_update_url");
        $buzz = $this->container->get('buzz');
        $url = $this->getUrlWithAuthorizeParameters($api_url);
        $result = json_decode($buzz->post($url,array(),json_encode((array)$client))->getContent());
        $this->statusCheck($result);
        return $result;
    }

    public function confirm($token)
    {
        $api_url = $this->container->getParameter("center_confirm_url");
        $buzz = $this->container->get('buzz');
        $url = $this->getUrlWithAuthorizeParameters($api_url."/".$token);
        $result = json_decode($buzz->get($url)->getContent());
        $this->statusCheck($result);
        return $result;
    }

    public function uniqueUsername($value)
    {
        $api_url = $this->container->getParameter("center_unique_username_url");
        $buzz = $this->container->get('buzz');
        $url = $this->getUrlWithAuthorizeParameters($api_url."/".$value);
        $result = json_decode($buzz->get($url)->getContent());
        $this->statusCheck($result);
        return $result;
    }

    public function uniqueEmail($value)
    {
        $api_url = $this->container->getParameter("center_unique_email_url");
        $buzz = $this->container->get('buzz');
        $url = $this->getUrlWithAuthorizeParameters($api_url."/".$value);
        $result = json_decode($buzz->get($url)->getContent());
        $this->statusCheck($result);
        return $result;
    }

    public function getData($atpressId)
    {
        $api_url = $this->container->getParameter("center_get_data_url");
        $buzz = $this->container->get('buzz');
        $url = $this->getUrlWithAuthorizeParameters($api_url."/".$atpressId);
        $result = json_decode($buzz->get($url)->getContent());
        $this->statusCheck($result);
        return $result;
    }

    public function loadUser($username)
    {
        $api_url = $this->container->getParameter("center_load_user_url");
        $buzz = $this->container->get('buzz');
        $url = $this->getUrlWithAuthorizeParameters($api_url."/".$username);
        $result = json_decode($buzz->get($url)->getContent());
        $this->statusCheck($result);
        return $result->data;
    }

    public function searchData($conditions)
    {
        $api_url = $this->container->getParameter("center_search_data_v2_url");
        $buzz = $this->container->get('buzz');
        $url = $this->getUrlWithAuthorizeParameters($api_url);
        $query['service'] = $this->getServiceCode();
        $query['conditions'] = $conditions;
        $result = json_decode($buzz->post($url,array(),json_encode($query))->getContent());
        $this->statusCheck($result);
        return $result->data;
    }

    public function fetchData($ids)
    {
        $api_url = $this->container->getParameter("center_fetch_data_url");
        $buzz = $this->container->get('buzz');
        $url = $this->getUrlWithAuthorizeParameters($api_url);
        $query['ids'] = $ids;
        $result = json_decode($buzz->post($url,array(),json_encode($query))->getContent());
        $this->statusCheck($result);
        return $result->data;
    }

    public function mergeCenterData($users,$data)
    {
        foreach ($users as $user){
            foreach ($data as $value){
                if ($user->getAtpressId()==$value->atpressId){
                    $this->setUser($user,$value);
                    break;
                }
            }
        }
        return $users;
    }

    public function fetchCenterData($user)
    {
        $result = $this->getData($user->getAtpressId());
        if (!is_null($result->data)) $this->setUser($user,$result->data);
    }

    public function setUser($user,$value)
    {
        $user->setAtpressId($value->atpressId);
        $user->setUsername($value->username);
        $user->setPassword($value->password);
        $user->setEnabled($value->enabled);
        if ($value->locked == 1)
        {
            $user->setExpired($value->locked);
        }
//        $user->setLocked($value->locked);
//        $user->setExpired($value->expired);
        $user->setType($value->type);
        $user->setCompany($value->company);
        $user->setCompanyKana($value->companyKana);
        $user->setRepresentative($value->representative);
        $user->setRepresentativeCharge($value->representativeCharge);
        $user->setLocation($value->location);
        $user->setListed($value->listed);
        $user->setSecuritiesCode($value->securitiesCode);
        $user->setUrl($value->url);

        $user->setLastName($value->lastName);
        $user->setFirstName($value->firstName);
        $user->setLastNameKana($value->lastNameKana);
        $user->setFirstNameKana($value->firstNameKana);
        $user->setDomain($value->domain);
        $user->setCharge($value->charge);
        $user->setZip($value->zip);
        $user->setPrefecture($value->prefecture);
        $user->setStreet($value->street);
        $user->setBuilding($value->building);
        $user->setTel($value->tel);
        $user->setEmergencyTel($value->emergencyTel);
        $user->setFax($value->fax);
        $user->setEmail($value->email);

        $user->setSubLastName($value->subLastName);
        $user->setSubFirstName($value->subFirstName);
        $user->setSubLastNameKana($value->subLastNameKana);
        $user->setSubFirstNameKana($value->subFirstNameKana);
        $user->setSubDomain($value->subDomain);
        $user->setSubCharge($value->subCharge);
        $user->setSubTel($value->subTel);
        $user->setSubEmail($value->subEmail);

        $user->setUseBillInfo($value->useBillInfo);
        $user->setBillCompany($value->billCompany);
        $user->setBillCompanyKana($value->billCompanyKana);
        $user->setBillTel($value->billTel);
        $user->setBillFax($value->billFax);
        $user->setBillEmail($value->billEmail);
        $user->setBillZip($value->billZip);
        $user->setBillPrefecture($value->billPrefecture);
        $user->setBillStreet($value->billStreet);
        $user->setBillBuilding($value->billBuilding);
        $user->setBillLastName($value->billLastName);
        $user->setBillFirstName($value->billFirstName);
        $user->setBillLastNameKana($value->billLastNameKana);
        $user->setBillFirstNameKana($value->billLastNameKana);
        $user->setBillDomain($value->billDomain);
        $user->setBillCharge($value->billCharge);
        $user->setBillAccount($value->billAccount);
        $user->setBillClosingDay($value->billClosingDay);
        $user->setBillPaymentCycle($value->billPaymentCycle);
        $user->setBillPaymentDay($value->billPaymentDay);
        $user->setServices($value->services);
    }

    private function getUrlWithAuthorizeParameters($url)
    {
        $client_id = $this->container->getParameter("center_client_id");
        $client_secret = $this->container->getParameter("center_client_secret");
        return $url."?"."client_id=".$client_id."&"."client_secret=".$client_secret;
    }

    private function statusCheck($result)
    {
        if (isset($result->status))
        {
            if ($result->status!="ok")
                throw new NotFoundHttpException($result->status);
        }
        else
        {
            throw new NotFoundHttpException("api error");
        }
    }
}

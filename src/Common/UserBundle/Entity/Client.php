<?php
namespace Common\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;

class Client extends BaseUser{

    protected $atpressId;
    protected $username;
    protected $plainPassword;
    protected $type;
    protected $company;
    protected $companyKana;
    protected $representative;
    protected $representativeCharge;
    protected $location;
    protected $listed;
    protected $securitiesCode;
    protected $url;
    protected $lastName;
    protected $firstName;
    protected $lastNameKana;
    protected $firstNameKana;
    protected $domain;
    protected $charge;
    protected $zip;
    protected $prefecture;
    protected $street;
    protected $building;
    protected $tel;
    protected $emergencyTel;
    protected $fax;
    protected $email;
    protected $subLastName;
    protected $subFirstName;
    protected $subLastNameKana;
    protected $subFirstNameKana;
    protected $subDomain;
    protected $subCharge;
    protected $subTel;
    protected $subEmail;
    protected $useBillInfo;
    protected $billCompany;
    protected $billCompanyKana;
    protected $billTel;
    protected $billFax;
    protected $billEmail;
    protected $billZip;
    protected $billPrefecture;
    protected $billStreet;
    protected $billBuilding;
    protected $billLastName;
    protected $billFirstName;
    protected $billLastNameKana;
    protected $billFirstNameKana;
    protected $billDomain;
    protected $billCharge;
    protected $billAccount;
    protected $paymentSite;
    protected $blackRank;
    protected $paymentMemo;
    protected $services;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $this->getUsernameCanonical()."-".$emailCanonical;
    }

    /**
     * Set atpressId
     *
     * @param integer $atpressId
     */
    public function setAtpressId($atpressId)
    {
        $this->atpressId = $atpressId;
    }

    /**
     * Get atpressId
     *
     * @return integer
     */
    public function getAtpressId()
    {
        return $this->atpressId;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set plainPassword
     *
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * Get plainPassword
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }


    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set company
     *
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set companyKana
     *
     * @param string $companyKana
     */
    public function setCompanyKana($companyKana)
    {
        $this->companyKana = $companyKana;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompanyKana()
    {
        return $this->companyKana;
    }


    /**
     * Set representative
     *
     * @param string $representative
     */
    public function setRepresentative($representative)
    {
        $this->representative = $representative;
    }

    /**
     * Get representative
     *
     * @return string
     */
    public function getRepresentative()
    {
        return $this->representative;
    }

    /**
     * Set representativeCharge
     *
     * @param string $representativeCharge
     */
    public function setRepresentativeCharge($representativeCharge)
    {
        $this->representativeCharge = $representativeCharge;
    }

    /**
     * Get representativeCharge
     *
     * @return string
     */
    public function getRepresentativeCharge()
    {
        return $this->representativeCharge;
    }

    /**
     * Set location
     *
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set listed
     *
     * @param string $listed
     */
    public function setListed($listed)
    {
        $this->listed = $listed;
    }

    /**
     * Get listed
     *
     * @return string
     */
    public function getListed()
    {
        return $this->listed;
    }

    /**
     * Set securitiesCode
     *
     * @param string $securitiesCode
     */
    public function setSecuritiesCode($securitiesCode)
    {
        $this->securitiesCode = $securitiesCode;
    }

    /**
     * Get securitiesCode
     *
     * @return string
     */
    public function getSecuritiesCode()
    {
        return $this->securitiesCode;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }



    /**
     * Set lastNameKana
     *
     * @param string $lastNameKana
     */
    public function setLastNameKana($lastNameKana)
    {
        $this->lastNameKana = $lastNameKana;
    }

    /**
     * Get lastNameKana
     *
     * @return string
     */
    public function getLastNameKana()
    {
        return $this->lastNameKana;
    }

    /**
     * Set firstNameKana
     *
     * @param string $firstNameKana
     */
    public function setFirstNameKana($firstNameKana)
    {
        $this->firstNameKana = $firstNameKana;
    }

    /**
     * Get firstNameKana
     *
     * @return string
     */
    public function getFirstNameKana()
    {
        return $this->firstNameKana;
    }

    /**
     * Set domain
     *
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set charge
     *
     * @param string $charge
     */
    public function setCharge($charge)
    {
        $this->charge = $charge;
    }

    /**
     * Get charge
     *
     * @return string
     */
    public function getCharge()
    {
        return $this->charge;
    }

    /**
     * Set zip
     *
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set prefecture
     *
     * @param integer $prefecture
     */
    public function setPrefecture($prefecture)
    {
        $this->prefecture = $prefecture;
    }

    /**
     * Get prefecture
     *
     * @return integer
     */
    public function getPrefecture()
    {
        return $this->prefecture;
    }

    /**
     * Set street
     *
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set building
     *
     * @param string $building
     */
    public function setBuilding($building)
    {
        $this->building = $building;
    }

    /**
     * Get building
     *
     * @return string
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set tel
     *
     * @param string $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set emergencyTel
     *
     * @param string $emergencyTel
     */
    public function setEmergencyTel($emergencyTel)
    {
        $this->emergencyTel = $emergencyTel;
    }

    /**
     * Get emergencyTel
     *
     * @return string
     */
    public function getEmergencyTel()
    {
        return $this->emergencyTel;
    }

    /**
     * Set fax
     *
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set subLastName
     *
     * @param string $subLastName
     */
    public function setSubLastName($subLastName)
    {
        $this->subLastName = $subLastName;
    }

    /**
     * Get subLastName
     *
     * @return string
     */
    public function getSubLastName()
    {
        return $this->subLastName;
    }

    /**
     * Set subFirstName
     *
     * @param string $subFirstName
     */
    public function setSubFirstName($subFirstName)
    {
        $this->subFirstName = $subFirstName;
    }

    /**
     * Get subFirstName
     *
     * @return string
     */
    public function getSubFirstName()
    {
        return $this->subFirstName;
    }

    /**
     * Set subDomain
     *
     * @param string $subDomain
     */
    public function setSubDomain($subDomain)
    {
        $this->subDomain = $subDomain;
    }

    /**
     * Set subLastNameKana
     *
     * @param string $subLastNameKana
     */
    public function setSubLastNameKana($subLastNameKana)
    {
        $this->subLastNameKana = $subLastNameKana;
    }

    /**
     * Get subLastNameKana
     *
     * @return string
     */
    public function getSubLastNameKana()
    {
        return $this->subLastNameKana;
    }

    /**
     * Set subFirstNameKana
     *
     * @param string $subFirstNameKana
     */
    public function setSubFirstNameKana($subFirstNameKana)
    {
        $this->subFirstNameKana = $subFirstNameKana;
    }

    /**
     * Get subFirstNameKana
     *
     * @return string
     */
    public function getSubFirstNameKana()
    {
        return $this->subFirstNameKana;
    }

    /**
     * Get subDomain
     *
     * @return string
     */
    public function getSubDomain()
    {
        return $this->subDomain;
    }

    /**
     * Set subCharge
     *
     * @param string $subCharge
     */
    public function setSubCharge($subCharge)
    {
        $this->subCharge = $subCharge;
    }

    /**
     * Get subCharge
     *
     * @return string
     */
    public function getSubCharge()
    {
        return $this->subCharge;
    }

    /**
     * Set subTel
     *
     * @param string $subTel
     */
    public function setSubTel($subTel)
    {
        $this->subTel = $subTel;
    }

    /**
     * Get subTel
     *
     * @return string
     */
    public function getSubTel()
    {
        return $this->subTel;
    }

    /**
     * Set subEmail
     *
     * @param string $subEmail
     */
    public function setSubEmail($subEmail)
    {
        $this->subEmail = $subEmail;
    }

    /**
     * Get subEmail
     *
     * @return string
     */
    public function getSubEmail()
    {
        return $this->subEmail;
    }

    /**
     * Set useBillInfo
     *
     * @param smallint $useBillInfo
     */
    public function setUseBillInfo($useBillInfo)
    {
        $this->useBillInfo = $useBillInfo;
    }

    /**
     * Get useBillInfo
     *
     * @return smallint
     */
    public function getUseBillInfo()
    {
        return $this->useBillInfo;
    }


    /**
     * Set billCompany
     *
     * @param string $billCompany
     */
    public function setBillCompany($billCompany)
    {
        $this->billCompany = $billCompany;
    }

    /**
     * Get billCompany
     *
     * @return string
     */
    public function getBillCompany()
    {
        return $this->billCompany;
    }

    /**
     * Set billZip
     *
     * @param string $billZip
     */
    public function setBillZip($billZip)
    {
        $this->billZip = $billZip;
    }

    /**
     * Get billZip
     *
     * @return string
     */
    public function getBillZip()
    {
        return $this->billZip;
    }

    /**
     * Set billPrefecture
     *
     * @param integer $billPrefecture
     */
    public function setBillPrefecture($billPrefecture)
    {
        $this->billPrefecture = $billPrefecture;
    }

    /**
     * Get billPrefecture
     *
     * @return integer
     */
    public function getBillPrefecture()
    {
        return $this->billPrefecture;
    }

    /**
     * Set billStreet
     *
     * @param string $billStreet
     */
    public function setBillStreet($billStreet)
    {
        $this->billStreet = $billStreet;
    }

    /**
     * Get billStreet
     *
     * @return string
     */
    public function getBillStreet()
    {
        return $this->billStreet;
    }

    /**
     * Set billBuilding
     *
     * @param string $billBuilding
     */
    public function setBillBuilding($billBuilding)
    {
        $this->billBuilding = $billBuilding;
    }

    /**
     * Get billBuilding
     *
     * @return string
     */
    public function getBillBuilding()
    {
        return $this->billBuilding;
    }

    /**
     * Set billLastName
     *
     * @param string $billLastName
     */
    public function setBillLastName($billLastName)
    {
        $this->billLastName = $billLastName;
    }

    /**
     * Get billLastName
     *
     * @return string
     */
    public function getBillLastName()
    {
        return $this->billLastName;
    }

    /**
     * Set billFirstName
     *
     * @param string $billFirstName
     */
    public function setBillFirstName($billFirstName)
    {
        $this->billFirstName = $billFirstName;
    }

    /**
     * Get billFirstName
     *
     * @return string
     */
    public function getBillFirstName()
    {
        return $this->billFirstName;
    }

    /**
     * Set billLastNameKana
     *
     * @param string $billLastNameKana
     */
    public function setBillLastNameKana($billLastNameKana)
    {
        $this->billLastNameKana = $billLastNameKana;
    }

    /**
     * Get billLastNameKana
     *
     * @return string
     */
    public function getBillLastNameKana()
    {
        return $this->billLastNameKana;
    }

    /**
     * Set billFirstNameKana
     *
     * @param string $billFirstNameKana
     */
    public function setBillFirstNameKana($billFirstNameKana)
    {
        $this->billFirstNameKana = $billFirstNameKana;
    }

    /**
     * Get billFirstNameKana
     *
     * @return string
     */
    public function getBillFirstNameKana()
    {
        return $this->billFirstNameKana;
    }

    /**
     * Set billDomain
     *
     * @param string $billDomain
     */
    public function setBillDomain($billDomain)
    {
        $this->billDomain = $billDomain;
    }

    /**
     * Get billDomain
     *
     * @return string
     */
    public function getBillDomain()
    {
        return $this->billDomain;
    }

    /**
     * Set billCharge
     *
     * @param string $billCharge
     */
    public function setBillCharge($billCharge)
    {
        $this->billCharge = $billCharge;
    }

    /**
     * Get billCharge
     *
     * @return string
     */
    public function getBillCharge()
    {
        return $this->billCharge;
    }

    /**
     * Set billAccount
     *
     * @param string $billAccount
     */
    public function setBillAccount($billAccount)
    {
        $this->billAccount = $billAccount;
    }

    /**
     * Get billAccount
     *
     * @return string
     */
    public function getBillAccount()
    {
        return $this->billAccount;
    }

    /**
     * Set paymentSite
     *
     * @param string $paymentSite
     */
    public function setPaymentSite($paymentSite)
    {
        $this->paymentSite = $paymentSite;
    }

    /**
     * Get paymentSite
     *
     * @return string
     */
    public function getPaymentSite()
    {
        return $this->paymentSite;
    }

    /**
     * Set blackRank
     *
     * @param string $blackRank
     */
    public function setBlackRank($blackRank)
    {
        $this->blackRank = $blackRank;
    }

    /**
     * Get blackRank
     *
     * @return string
     */
    public function getBlackRank()
    {
        return $this->blackRank;
    }

    /**
     * Set paymentMemo
     *
     * @param text $paymentMemo
     */
    public function setPaymentMemo($paymentMemo)
    {
        $this->paymentMemo = $paymentMemo;
    }

    /**
     * Get paymentMemo
     *
     * @return text
     */
    public function getPaymentMemo()
    {
        return $this->paymentMemo;
    }

    /**
     * Set billCompanyKana
     *
     * @param string $billCompanyKana
     */
    public function setBillCompanyKana($billCompanyKana)
    {
        $this->billCompanyKana = $billCompanyKana;
    }

    /**
     * Get billCompanyKana
     *
     * @return string
     */
    public function getBillCompanyKana()
    {
        return $this->billCompanyKana;
    }

    /**
     * Set billTel
     *
     * @param string $billTel
     */
    public function setBillTel($billTel)
    {
        $this->billTel = $billTel;
    }

    /**
     * Get billTel
     *
     * @return string
     */
    public function getBillTel()
    {
        return $this->billTel;
    }

    /**
     * Set billFax
     *
     * @param string $billFax
     */
    public function setBillFax($billFax)
    {
        $this->billFax = $billFax;
    }

    /**
     * Get billFax
     *
     * @return string
     */
    public function getBillFax()
    {
        return $this->billFax;
    }

    /**
     * Set billEmail
     *
     * @param string $billEmail
     */
    public function setBillEmail($billEmail)
    {
        $this->billEmail = $billEmail;
    }

    /**
     * Get billEmail
     *
     * @return string
     */
    public function getBillEmail()
    {
        return $this->billEmail;
    }

    /**
     * Set adminMemo
     *
     * @param text $adminMemo
     */
    public function setAdminMemo($adminMemo)
    {
        $this->adminMemo = $adminMemo;
    }

    /**
     * Get adminMemo
     *
     * @return text
     */
    public function getAdminMemo()
    {
        return $this->adminMemo;
    }

    /**
     * Set services
     *
     * @param array $services
     */
    public function setServices($services)
    {
        $this->services = $services;
    }

    /**
     * Get services
     *
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }

    public function addService($service)
    {
        $service = strtoupper($service);
        if (!in_array($service, $this->services, true)) {
            $this->services[] = $service;
        }
    }

    public function hasService($service)
    {
        return in_array(strtoupper($service), $this->getServices(), true);
    }

    public function getName(){
        return $this->getLastName()." ".$this->getFirstName();
    }

    public function getSubName(){
        return $this->getSubLastName()." ".$this->getSubFirstName();
    }
} 
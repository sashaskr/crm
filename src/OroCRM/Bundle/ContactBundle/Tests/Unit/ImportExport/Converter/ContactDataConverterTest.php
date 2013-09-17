<?php

namespace OroCRM\Bundle\ContactBundle\Tests\Unit\ImportExport\Converter;

use OroCRM\Bundle\ContactBundle\ImportExport\Converter\ContactDataConverter;

class ContactDataConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContactDataConverter
     */
    protected $dataConverter;

    /**
     * @var array
     */
    protected $backendHeader = array(
        'id',
        'namePrefix',
        'firstName',
        'lastName',
        'nameSuffix',
        'gender',
        'description',
        'jobTitle',
        'fax',
        'skype',
        'twitter',
        'facebook',
        'googlePlus',
        'linkedIn',
        'birthday',
        'source',
        'method',
        'owner:firstName',
        'owner:lastName',
        'assignedTo:firstName',
        'assignedTo:lastName',
        'addresses:0:label',
        'addresses:0:firstName',
        'addresses:0:lastName',
        'addresses:0:street',
        'addresses:0:street2',
        'addresses:0:city',
        'addresses:0:state',
        'addresses:0:country',
        'addresses:0:postalCode',
        'addresses:0:types:0',
        'addresses:0:types:1',
        'addresses:1:label',
        'addresses:1:firstName',
        'addresses:1:lastName',
        'addresses:1:street',
        'addresses:1:street2',
        'addresses:1:city',
        'addresses:1:state',
        'addresses:1:country',
        'addresses:1:postalCode',
        'addresses:1:types:0',
        'addresses:1:types:1',
        'emails:0',
        'emails:1',
        'phones:0',
        'phones:1',
        'groups:0',
        'groups:1',
        'accounts:0',
        'accounts:1',
    );

    protected function setUp()
    {
        $headerProvider
            = $this->getMockBuilder('OroCRM\Bundle\ContactBundle\ImportExport\Provider\ContactHeaderProvider')
                ->disableOriginalConstructor()
                ->setMethods(array('getHeader'))
                ->getMock();
        $headerProvider->expects($this->any())
            ->method('getHeader')
            ->will($this->returnValue($this->backendHeader));

        $this->dataConverter = new ContactDataConverter($headerProvider);
    }

    protected function tearDown()
    {
        unset($this->dataConverter);
    }

    /**
     * @param array $importedRecord
     * @param array $result
     * @dataProvider convertToExportFormatDataProvider
     */
    public function testConvertToExportFormat(array $importedRecord, array $result)
    {
        $this->assertEquals($result, $this->dataConverter->convertToExportFormat($importedRecord));
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function convertToExportFormatDataProvider()
    {
        return array(
            'minimal data' => array(
                'importedRecord' => array(
                    'firstName' => 'John',
                    'lastName'  => 'Doe',
                ),
                'result' => array(
                    'ID' => '',
                    'Name Prefix' => '',
                    'First Name' => 'John',
                    'Last Name' => 'Doe',
                    'Name Suffix' => '',
                    'gender' => '',
                    'Description' => '',
                    'Job Title' => '',
                    'Fax' => '',
                    'Skype' => '',
                    'Twitter' => '',
                    'Facebook' => '',
                    'GooglePlus' => '',
                    'LinkedIn' => '',
                    'Birthday' => '',
                    'Source' => '',
                    'Method' => '',
                    'Owner First Name' => '',
                    'Owner Last Name' => '',
                    'Assigned To First Name' => '',
                    'Assigned To Last Name' => '',
                    'Address Label' => '',
                    'Address First Name' => '',
                    'Address Last Name' => '',
                    'Address Street' => '',
                    'Address Street2' => '',
                    'Address City' => '',
                    'Address State' => '',
                    'Address Country' => '',
                    'Address Postal Code' => '',
                    'Address Type' => '',
                    'Address Type 1' => '',
                    'Address 1 Label' => '',
                    'Address 1 First Name' => '',
                    'Address 1 Last Name' => '',
                    'Address 1 Street' => '',
                    'Address 1 Street2' => '',
                    'Address 1 City' => '',
                    'Address 1 State' => '',
                    'Address 1 Country' => '',
                    'Address 1 Postal Code' => '',
                    'Address 1 Type' => '',
                    'Address 1 Type 1' => '',
                    'Email' => '',
                    'Email 1' => '',
                    'Phone' => '',
                    'Phone 1' => '',
                    'Group' => '',
                    'Group 1' => '',
                    'Account' => '',
                    'Account 1' => '',
                )
            ),
            'full data' => array(
                'importedRecord' => array(
                    'id' => 69,
                    'namePrefix' => 'Mr.',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'nameSuffix' => 'Jr.',
                    'gender' => 'male',
                    'description' => 'some person',
                    'jobTitle' => 'Engineer',
                    'fax' => '444',
                    'skype' => 'john.doe',
                    'twitter' => 'john.doe.twitter',
                    'facebook' => 'john.doe.facebook',
                    'googlePlus' => 'john.doe.googlePlus',
                    'linkedIn' => 'john.doe.linkedIn',
                    'birthday' => '1944-08-29T16:52:09+0200',
                    'source' => 'tv',
                    'method' => 'email',
                    'owner' => array(
                        'firstName' => 'William',
                        'lastName' => 'Stewart',
                    ),
                    'assignedTo' => array(
                        'firstName' => 'William',
                        'lastName' => 'Stewart',
                    ),
                    'addresses' => array(
                        array(
                            'label' => 'Billing Address',
                            'firstName' => 'John',
                            'lastName' => 'Doe',
                            'street' => 'First Street',
                            'street2' => null,
                            'city' => 'London',
                            'state' => 'ENG',
                            'country' => 'UK',
                            'postalCode' => '555666777',
                            'types' => array('billing')
                        ),
                        array(
                            'label' => 'Shipping Address',
                            'firstName' => 'Jane',
                            'lastName' => 'Smith',
                            'street' => 'Second street',
                            'street2' => '2nd',
                            'city' => 'London',
                            'state' => 'ENG',
                            'country' => 'UK',
                            'postalCode' => '777888999',
                            'types' => array('shipping')
                        ),
                    ),
                    'emails' => array(
                        'john@example.com',
                        'doe@example.com',
                    ),
                    'phones' => array(
                        '0 800 11 22 444',
                        '0 800 11 22 555',
                    ),
                    'groups' => array(
                        'first_group',
                        'second_group',
                    ),
                    'accounts' => array(
                        'First Company',
                        'Second Company',
                    )
                ),
                'result' => array (
                    'ID' => '69',
                    'Name Prefix' => 'Mr.',
                    'First Name' => 'John',
                    'Last Name' => 'Doe',
                    'Name Suffix' => 'Jr.',
                    'gender' => 'male',
                    'Description' => 'some person',
                    'Job Title' => 'Engineer',
                    'Fax' => '444',
                    'Skype' => 'john.doe',
                    'Twitter' => 'john.doe.twitter',
                    'Facebook' => 'john.doe.facebook',
                    'GooglePlus' => 'john.doe.googlePlus',
                    'LinkedIn' => 'john.doe.linkedIn',
                    'Birthday' => '1944-08-29T16:52:09+0200',
                    'Source' => 'tv',
                    'Method' => 'email',
                    'Owner First Name' => 'William',
                    'Owner Last Name' => 'Stewart',
                    'Assigned To First Name' => 'William',
                    'Assigned To Last Name' => 'Stewart',
                    'Address Label' => 'Billing Address',
                    'Address First Name' => 'John',
                    'Address Last Name' => 'Doe',
                    'Address Street' => 'First Street',
                    'Address Street2' => '',
                    'Address City' => 'London',
                    'Address State' => 'ENG',
                    'Address Country' => 'UK',
                    'Address Postal Code' => '555666777',
                    'Address Type' => 'billing',
                    'Address Type 1' => '',
                    'Address 1 Label' => 'Shipping Address',
                    'Address 1 First Name' => 'Jane',
                    'Address 1 Last Name' => 'Smith',
                    'Address 1 Street' => 'Second street',
                    'Address 1 Street2' => '2nd',
                    'Address 1 City' => 'London',
                    'Address 1 State' => 'ENG',
                    'Address 1 Country' => 'UK',
                    'Address 1 Postal Code' => '777888999',
                    'Address 1 Type' => 'shipping',
                    'Address 1 Type 1' => '',
                    'Email' => 'john@example.com',
                    'Email 1' => 'doe@example.com',
                    'Phone' => '0 800 11 22 444',
                    'Phone 1' => '0 800 11 22 555',
                    'Group' => 'first_group',
                    'Group 1' => 'second_group',
                    'Account' => 'First Company',
                    'Account 1' => 'Second Company',
                )
            ),
        );
    }

    /**
     * @param array $exportedRecord
     * @param array $result
     * @dataProvider convertToImportFormatDataProvider
     */
    public function testConvertToImportFormat(array $exportedRecord, array $result)
    {
        $this->assertEquals($result, $this->dataConverter->convertToImportFormat($exportedRecord));
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function convertToImportFormatDataProvider()
    {
        return array(
            'minimal data' => array(
                'exportedRecord' => array(
                    'First Name' => 'John',
                    'Last Name'  => 'Doe',
                ),
                'result' => array(
                    'firstName' => 'John',
                    'lastName'  => 'Doe',
                )
            ),
            'full data' => array(
                'exportedRecord' => array(
                    'ID' => '69',
                    'Name Prefix' => 'Mr.',
                    'First Name' => 'John',
                    'Last Name' => 'Doe',
                    'Name Suffix' => 'Jr.',
                    'gender' => 'male',
                    'Description' => 'some person',
                    'Job Title' => 'Engineer',
                    'Fax' => '444',
                    'Skype' => 'john.doe',
                    'Twitter' => 'john.doe.twitter',
                    'Facebook' => 'john.doe.facebook',
                    'GooglePlus' => 'john.doe.googlePlus',
                    'LinkedIn' => 'john.doe.linkedIn',
                    'Birthday' => '1944-08-29T16:52:09+0200',
                    'Source' => 'tv',
                    'Method' => 'email',
                    'Owner First Name' => 'William',
                    'Owner Last Name' => 'Stewart',
                    'Assigned To First Name' => 'William',
                    'Assigned To Last Name' => 'Stewart',
                    'Address Label' => 'Billing Address',
                    'Address First Name' => 'John',
                    'Address Last Name' => 'Doe',
                    'Address Street' => 'First Street',
                    'Address Street2' => '',
                    'Address City' => 'London',
                    'Address State' => 'ENG',
                    'Address Country' => 'UK',
                    'Address Postal Code' => '555666777',
                    'Address Type' => 'billing',
                    'Address Type 1' => '',
                    'Address 1 Label' => 'Shipping Address',
                    'Address 1 First Name' => 'Jane',
                    'Address 1 Last Name' => 'Smith',
                    'Address 1 Street' => 'Second street',
                    'Address 1 Street2' => '2nd',
                    'Address 1 City' => 'London',
                    'Address 1 State' => 'ENG',
                    'Address 1 Country' => 'UK',
                    'Address 1 Postal Code' => '777888999',
                    'Address 1 Type' => 'shipping',
                    'Address 1 Type 1' => '',
                    'Email' => 'john@example.com',
                    'Email 1' => 'doe@example.com',
                    'Phone' => '0 800 11 22 444',
                    'Phone 1' => '0 800 11 22 555',
                    'Group' => 'first_group',
                    'Group 1' => 'second_group',
                    'Account' => 'First Company',
                    'Account 1' => 'Second Company',
                ),
                'result' => array(
                    'id' => 69,
                    'namePrefix' => 'Mr.',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'nameSuffix' => 'Jr.',
                    'gender' => 'male',
                    'description' => 'some person',
                    'jobTitle' => 'Engineer',
                    'fax' => '444',
                    'skype' => 'john.doe',
                    'twitter' => 'john.doe.twitter',
                    'facebook' => 'john.doe.facebook',
                    'googlePlus' => 'john.doe.googlePlus',
                    'linkedIn' => 'john.doe.linkedIn',
                    'birthday' => '1944-08-29T16:52:09+0200',
                    'source' => 'tv',
                    'method' => 'email',
                    'owner' => array(
                        'firstName' => 'William',
                        'lastName' => 'Stewart',
                    ),
                    'assignedTo' => array(
                        'firstName' => 'William',
                        'lastName' => 'Stewart',
                    ),
                    'addresses' => array(
                        array(
                            'label' => 'Billing Address',
                            'firstName' => 'John',
                            'lastName' => 'Doe',
                            'street' => 'First Street',
                            'city' => 'London',
                            'state' => 'ENG',
                            'country' => 'UK',
                            'postalCode' => '555666777',
                            'types' => array('billing')
                        ),
                        array(
                            'label' => 'Shipping Address',
                            'firstName' => 'Jane',
                            'lastName' => 'Smith',
                            'street' => 'Second street',
                            'street2' => '2nd',
                            'city' => 'London',
                            'state' => 'ENG',
                            'country' => 'UK',
                            'postalCode' => '777888999',
                            'types' => array('shipping')
                        ),
                    ),
                    'emails' => array(
                        'john@example.com',
                        'doe@example.com',
                    ),
                    'phones' => array(
                        '0 800 11 22 444',
                        '0 800 11 22 555',
                    ),
                    'groups' => array(
                        'first_group',
                        'second_group',
                    ),
                    'accounts' => array(
                        'First Company',
                        'Second Company',
                    )
                )
            ),
        );
    }
}

@api
Feature: All uploaded programs have to be validated.

  Background: 
    Given I am a valid user

  Scenario: program must have a code.xml
    When I upload a program with a missing code.xml
    Then I should get the json object:
      """
      {"statusCode":507,"answer":"unknown error: project_xml_not_found!","preHeaderMessages":""}
      """

  Scenario: program must have a valid code.xml
    When I upload a program with an invalid code.xml
    Then I should get the json object:
      """
      {"statusCode":508,"answer":"invalid code xml","preHeaderMessages":""}
      """

  @disabled
  Scenario: program with missing images are rejected
    When I upload a program with a missing image
    Then I should get the json object:
      """
      {"statusCode":524,"answer":"Project XML mentions a file which does not exist in project-folder","preHeaderMessages":""}
      """

  @disabled
  Scenario: program with media files not defined in xml are rejected
    When I upload a program with an additional image
    Then I should get the json object:
      """
      {"statusCode":525,"answer":"unexpected file found","preHeaderMessages":""}
      """

  Scenario: invalid catrobat program files should be rejected
    When I upload an invalid program file
    Then I should get the json object:
      """
      {"statusCode":505,"answer":"invalid file","preHeaderMessages":""}
      """

  Scenario Outline: user should not be able to upload a program with an old pocketcode version
    Given I am using pocketcode for "<Platform>" with version "<Version>"
    When I upload a program
    Then I should get the json object:
    """
      {"statusCode":519,"answer":"Sorry, you are using an old version of Pocket Code. Please update to the lastest version.","preHeaderMessages":""}
    """

    Examples:
    | Platform | Version   |
    | Android  |   0.5     |
    | Windows  |   0.0.1   |
    | iOS      |   0.0.1   |

  Scenario Outline: user should not be able to upload a program with an old pocketcode version
    Given I am using pocketcode for "<Platform>" with version "<Version>"
    When I upload a program
    Then The upload should be successful

    Examples:
    | Platform | Version   |
    | Android  |   0.9.1   |
    | Android  |  v0.9.1   |
    | Windows  |   0.1.0   |
    | iOS      |   0.1.0   |


  Scenario: user should not be able to upload a program with an old language version
    Given I am using pocketcode with language version "0.7"
    When I upload a program
    Then I should get the json object:
    """
      {"statusCode":518,"answer":"Sorry, your programm contains an old version of the Catrobat language! Are you using the latest version of Pocket Code?","preHeaderMessages":""}
    """

      
  Scenario: A valid file must contain at least one screenshot and image and sound directories

  Scenario: All media files except screenshots have to be defined in code.xml

  Scenario: A program must have a name.

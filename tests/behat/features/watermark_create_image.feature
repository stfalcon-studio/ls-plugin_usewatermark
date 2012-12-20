Feature: Usewatermark plugin standart features BDD
  Test base functionality of LiveStreet Usewatermark plugin standart

  @mink:selenium2
    Scenario: Check is backup exists
      Then check is plugin active "usewatermark"
      Given I load fixtures for plugin "usewatermark"

      Given I am on "/login"
      Then I want to login as "admin"

      Given I am on "/topic/edit/3"
      Then I wait "1000"

      Then I send event "mouseup" to element by css ".editor-picture a"
      Then I fill event by css "#form-image-url-title" values "pic"
      When I put the file "test3.jpg" to "img_file"
      Then I send event "focus" to element by css "#topic_text"
      When I press "Submit"

      Then I wait "2000"
      Then I press "Publish"
      Then I wait "2000"

      Given I am on "/blog/3.html"
      Then I wait "1000"
      Then I check for backup of image

  @mink:selenium2
    Scenario: Check for adding watermark
      Then check is plugin active "usewatermark"
      Given I load fixtures for plugin "usewatermark"

      Given I am on "/login"
      Then I want to login as "admin"

      Given I am on "/topic/edit/3"
      Then I wait "1000"
      Then I send event "mouseup" to element by css ".editor-picture a"
      Then I fill event by css "#form-image-url-title" values "pic"
      When I put the file "test3.jpg" to "img_file"
      Then I send event "focus" to element by css "#topic_text"
      When I press "Submit"

      Then I wait "2000"
      Then I press "Publish"
      Then I wait "2000"

      Given I am on "/blog/3.html"
      Then I wait "1000"
      Then I check the image
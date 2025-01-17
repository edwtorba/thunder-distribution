<?php

namespace Drupal\Tests\thunder\FunctionalJavascript;

/**
 * Testing of Channels and Tags.
 *
 * @group Thunder
 *
 * @package Drupal\Tests\thunder\FunctionalJavascript
 */
class ChannelsTagsTest extends ThunderJavascriptTestBase {

  use ThunderArticleTestTrait;
  use ThunderParagraphsTestTrait;

  /**
   * Default user login role used for testing.
   *
   * @var string
   */
  protected static $defaultUserRole = 'administrator';

  /**
   * Test channel creation, tagging of articles and channel page with articles.
   */
  public function testChannelsCreation(): void {
    $this->drupalGet('admin/structure/taxonomy/manage/channel/add');

    // Create new Channel with 2 paragraphs.
    $this->setFieldValue('name[0][value]', 'New Section');
    $image1 = $this->loadMediaByUuid('5d719c64-7f32-4062-9967-9874f5ca3eba');
    $this->addImageParagraph('field_paragraphs', ['media:' . $image1->id()]);
    $this->addTextParagraph('field_paragraphs', 'Text for Channel');
    $this->clickSave();

    // Create 1. Article.
    $this->articleFillNew([
      'field_channel' => 6,
      'title[0][value]' => 'Article 1',
      'field_seo_title[0][value]' => 'Article 1',
      'field_tags[]' => ['New Section', 'Tag1'],
      'field_teaser_text[0][value]' => 'Teaser 1',
    ]);
    $image2 = $this->loadMediaByUuid('a4b2fa51-8340-4982-b792-92e060b71eb9');
    $this->selectMedia('field_teaser_media', 'image_browser', ['media:' . $image2->id()]);

    $this->addTextParagraph('field_paragraphs', 'Article Text 1');
    $this->setModerationState('published');
    $this->clickSave();

    // Create 2. Article.
    $this->articleFillNew([
      'field_channel' => 6,
      'title[0][value]' => 'Article 2',
      'field_seo_title[0][value]' => 'Article 2',
      'field_tags[]' => [[7, 'New Section'], 'Tag2'],
      'field_teaser_text[0][value]' => 'Teaser 2',
    ]);
    $image3 = $this->loadMediaByUuid('5bd93c54-469b-4ac7-927b-cf6bb1dcf3dd');
    $this->selectMedia('field_teaser_media', 'image_browser', ['media:' . $image3->id()]);

    $this->addTextParagraph('field_paragraphs', 'Article Text 2');
    $this->setModerationState('published');
    $this->clickSave();

    // Check is everything created properly for Article 1.
    $this->drupalGet('article-1');
    $tagLinks = $this->xpath("//div[contains(@class, 'field--name-field-tags')]//a");

    $this->assertSession()->pageTextContains('Article Text 1');
    $this->assertEquals(2, count($tagLinks));
    $this->assertSession()
      ->elementExists('xpath', "//div[contains(@class, 'field--name-field-tags')]//a[@href='/new-section-0' and text()='New Section']");
    $this->assertSession()
      ->elementExists('xpath', "//div[contains(@class, 'field--name-field-tags')]//a[@href='/tag1' and text()='Tag1']");

    // Check is everything created properly for Article 2.
    $this->drupalGet('article-2');
    $tagLinks = $this->xpath("//div[contains(@class, 'field--name-field-tags')]//a");

    $this->assertSession()->pageTextContains('Article Text 2');
    $this->assertEquals(2, count($tagLinks));
    $this->assertSession()
      ->elementExists('xpath', "//div[contains(@class, 'field--name-field-tags')]//a[@href='/new-section-0' and text()='New Section']");
    $this->assertSession()
      ->elementExists('xpath', "//div[contains(@class, 'field--name-field-tags')]//a[@href='/tag2' and text()='Tag2']");

    // Open Channel and check all teaser images and texts from added articles,
    // also channel image and text.
    $this->drupalGet('new-section');

    $this->createScreenshot($this->getScreenshotFolder() . '/ChannelsTagsTest_testChannelsCreation_' . date('Ymd_His') . '.png');

    $this->assertSession()
      ->elementExists('xpath', '//img[contains(@src, "picjumbo.com_HNCK7373.jpg")]');
    $this->assertSession()
      ->elementExists('xpath', '//img[contains(@src, "picjumbo.com_HNCK7731.jpg")]');
    $this->assertSession()
      ->elementExists('xpath', '//img[contains(@src, "thunder-city.jpg")]');

    $this->assertSession()->linkExists('Article 1');
    $this->assertSession()->linkExists('Article 2');

    $this->assertSession()->pageTextContains('Text for Channel');
    $this->assertSession()->pageTextContains('Teaser 1');
    $this->assertSession()->pageTextContains('Teaser 2');
  }

}

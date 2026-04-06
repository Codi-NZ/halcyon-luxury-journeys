<?php

namespace simple\simplemodule;

use Craft;
use yii\base\Event;
use yii\base\Module as BaseModule;

use craft\services\Plugins;

use craft\events\PluginEvent;
use craft\events\RegisterUrlRulesEvent;

use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;

use craft\web\View;
use craft\events\RegisterTemplateRootsEvent;
use craft\helpers\UrlHelper;

use simple\simplemodule\variables\SimpleVariable;
use simple\simplemodule\variables\HeadingVariable;
use simple\simplemodule\services\News;
use simple\simplemodule\twigextensions\HeadingTagObject;
/**
 * The 'simple' module
 *
 * @method static SimpleModule getInstance()
 */
class Module extends BaseModule
{
    public static Module $plugin;

    public function init(): void
    {
        Craft::setAlias('@simple', __DIR__);

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->request->isConsoleRequest) {
            $this->controllerNamespace = 'simple\\simplemodule\\console\\controllers';
        } else {
            $this->controllerNamespace = 'simple\\simplemodule\\controllers';
        }

        parent::init();

        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $e) {
                /** @var CraftVariable $variable */
                $variable = $e->sender;

                // Attach a behavior:
                // $variable->attachBehaviors([
                //     MyBehavior::class,
                // ]);

                // Attach a service:
                $variable->set('simple', SimpleVariable::class);
                
                // Attach heading variable for heading state management
                $variable->set('heading', HeadingVariable::class);
            }
        );

        $this->setComponents([
            "news" => News::class,
        ]);

        $this->attachEventHandlers();

        // Register global headingTag variables after Craft is initialized
        Craft::$app->onInit(function() {
            if (!Craft::$app->request->isConsoleRequest) {
                static $globalsRegistered = false;
                if (!$globalsRegistered) {
                    $headingVar = new HeadingVariable();
                    
                    // Auto-increment headingTag
                    $headingTagObj = new HeadingTagObject($headingVar, null);
                    
                    // Force specific levels: headingTag1, headingTag2, etc.
                    $headingTag1Obj = new HeadingTagObject($headingVar, 1);
                    $headingTag2Obj = new HeadingTagObject($headingVar, 2);
                    $headingTag3Obj = new HeadingTagObject($headingVar, 3);
                    $headingTag4Obj = new HeadingTagObject($headingVar, 4);
                    $headingTag5Obj = new HeadingTagObject($headingVar, 5);
                    $headingTag6Obj = new HeadingTagObject($headingVar, 6);
                    
                    $twig = Craft::$app->view->getTwig();
                    $twig->addGlobal('headingTag', $headingTagObj);
                    $twig->addGlobal('headingTag1', $headingTag1Obj);
                    $twig->addGlobal('headingTag2', $headingTag2Obj);
                    $twig->addGlobal('headingTag3', $headingTag3Obj);
                    $twig->addGlobal('headingTag4', $headingTag4Obj);
                    $twig->addGlobal('headingTag5', $headingTag5Obj);
                    $twig->addGlobal('headingTag6', $headingTag6Obj);
                    
                    $globalsRegistered = true;
                }
            }
        });
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/5.x/extend/events.html to get started)

        Event::on(
            View::class,
            View::EVENT_REGISTER_CP_TEMPLATE_ROOTS,
            function(RegisterTemplateRootsEvent $event) {
                $event->roots['simple'] = __DIR__ . '/templates';
            }
        );

        // Detect if a CP request is made to site settings, while there are
        // pending changes to project config files (and block the user!)
        Event::on(
            \craft\web\Application::class,
            \craft\web\Application::EVENT_BEFORE_REQUEST,
            function() {
                $request = Craft::$app->getRequest();

                if ($request->getIsCpRequest()) {
                    // The CP trigger (usually 'admin')
                    $cpTrigger = Craft::$app->getConfig()->getGeneral()->cpTrigger;
                    // The full CP path
                    $cpPath = $request->getPathInfo();

                    // detect if the path starts with 'settings' i.e. /admin/settings
                    if (strpos($cpPath, 'settings') === 0) {
                        // also check if there are pending changes to project config files
                        $projectConfig = Craft::$app->getProjectConfig();
                        if ($projectConfig->areChangesPending()) {
                            // bruh, go apply teh changes first! (redirect to /simple/pending-configs!)
                            return Craft::$app->getResponse()->redirect(UrlHelper::url('/' . $cpTrigger . '/simple/pending-configs'));
                        }
                    }

                }
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );
    }
}

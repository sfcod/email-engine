Symfony Email Engine Bundle
===========================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sfcod/email-engine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sfcod/email-engine/?branch=master)[![Code Climate](https://codeclimate.com/github/sfcod/email-engine/badges/gpa.svg)](https://codeclimate.com/github/sfcod/email-engine)

Provides possibility to send parametrized emails with attachments using queue of senders+repositories combination.

#### Config:
```yaml
sfcod_email_engine:
    main_sender: chained_sender
    senders:
        chained_sender:
            chain:
                senders: [db_swiftmailer_sender, twig_file_swiftmailer_sender]
        twig_file_swiftmailer_sender:
            sender:
                class: SfCod\EmailEngineBundle\Sender\SwiftMailerSender
            repository:
                class: SfCod\EmailEngineBundle\Repository\TwigFileRepository
        db_swiftmailer_sender:
            sender:
                class: SfCod\EmailEngineBundle\Sender\SwiftMailerSender
            repository:
                class: SfCod\EmailEngineBundle\Repository\DbRepository
                arguments: { entity: Common\Shared\Entity\Email, attribute: slug }
    templates:
        test_template: SfCod\EmailEngineBundle\Example\TestTemplate
```

Where "templates" section needed for "SfCod\EmailEngineBundle\Mailer\TemplateManager" service.
Using which you can get all possible email template parameters, description, etc.

#### Usage:

```php
use SfCod\EmailEngineBundle\Mailer\Mailer;
use SfCod\EmailEngineBundle\Example\TestOptions;
use SfCod\EmailEngineBundle\Example\TestTemplate;

public function indexAction(Mailer $mailer) {
    $options = new TestOptions('some message', 'attachment_path');
    $template = new TestTemplate($options);
    $mailer->send($template, 'example@email.com');
}
```

And TestTemplate email will be sent using SwiftMailerSender and template data will be collected from DbRepository.
If it fails, will be used SwiftMailerSender+TwigFileRepository, because both of them listed in "chained_sender" (see config section).
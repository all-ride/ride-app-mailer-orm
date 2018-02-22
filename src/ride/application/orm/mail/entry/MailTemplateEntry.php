<?php

namespace ride\application\orm\mail\entry;

use ride\application\orm\entry\MailTemplateEntry as OrmMailTemplateEntry;

use ride\library\mail\template\MailTemplate;
use ride\library\mail\type\MailType;

/**
 * Data container for a mail template
 */
class MailTemplateEntry extends OrmMailTemplateEntry implements MailTemplate {

    public function setMailType(MailType $mailType = null) {
        parent::setMailType($mailType);

        if ($mailType) {
            $this->setMailTypeName($mailType->getName());
        } else {
            $this->setMailTypeName(null);
        }
    }

}

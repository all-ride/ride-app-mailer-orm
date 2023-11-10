<?php

namespace ride\application\mail\template;

use ride\library\mail\exception\TemplateNotFoundMailException;
use ride\library\mail\template\MailTemplateProvider;
use ride\library\mail\template\MailTemplate;
use ride\library\mail\type\MailType;
use ride\library\orm\OrmManager;

/**
 * Interface for the provider of the mail templates
 */
class OrmMailTemplateProvider implements MailTemplateProvider {

    /**
     * Constructs a new ORM mail template provider
     * @param \ride\library\orm\OrmManager $ormManager
     * @return null
     */
    public function __construct(OrmManager $ormManager) {
        $this->ormManager = $ormManager;
        $this->model = $ormManager->getMailTemplateModel();
    }

    /**
     * Gets the available mail templates
     * @param array $options Options to fetch the mail templates. Available keys
     * are locale, query, limit, offset.
     * @return array Array with the id or machine name of the mail template as
     * key and an instance of the mail template as value
     * @see \ride\library\mail\template\MailTemplate
     */
    public function getMailTemplates(array $options) {

        $findOptions = array(
            'limit' => 10,
        );
        $locale = null;
        if (isset($options['locale'])) {
            $locale = $options['locale'];
        }

        if (isset($options['query'])) {
            $findOptions['query'] = $options['query'];
        }

        if (isset($options['limit'])) {
            $findOptions['limit'] = $options['limit'];
        }

        if (isset($options['page']) && $options['page']) {
            $page = $options['page'];

            $findOptions['page'] = $page;
        } else {
            $findOptions['page'] = 1;
        }

        return $this->model->find($findOptions, $locale, true);
    }

    /**
     * Gets the available mail templates for a specific mail type
     * @param \ride\library\mail\type\MailType $mailType Instance of the mail
     * type to filter on
     * @param string $locale Code of the locale
     * @return array Array with the id or machine name of the mail template as
     * key and an instance of the mail template as value
     * @see \ride\library\mail\template\MailTemplate
     */
    public function getMailTemplatesForType(MailType $mailType, $locale = null) {
        $findOptions = array();
        $findOptions['filter']['mailTypeName'] = $mailType->getName();

        return $this->model->find($findOptions, $locale, true);
    }

    /**
     * Gets a specific mail type
     * @param string $id Id or machine name of the mail template
     * @param string $locale Code of the locale
     * @return MailType Instance of the mail template
     * @throws TemplateNotFoundMailException when the mail template does not
     * exist
     */
    public function getMailTemplate($id, $locale) {
        $mailTemplate = $this->model->getById($id, $locale, true);
        if (!$mailTemplate) {
            throw new TemplateNotFoundMailException();
        }

        return $mailTemplate;
    }

    /**
     * Creates a new mail template for the provided locale
     * @param string $locale Code of the locale
     * @return \ride\library\mail\template\MailTemplate
     */
    public function createMailTemplate($locale) {
        $mailTemplate = $this->model->createEntry();
        $mailTemplate->setLocale($locale);

        return $mailTemplate;
    }

    /**
     * Saves the mail template
     * @param \ride\library\mail\template\MailTemplate $mailTemplate
     * @return null
     */
    public function saveMailTemplate(MailTemplate $mailTemplate) {
        $this->model->save($mailTemplate);
    }

    /**
     * Deletes the mail template
     * @param \ride\library\mail\template\MailTemplate $mailTemplate
     * @return null
     */
    public function deleteMailTemplate(MailTemplate $mailTemplate) {
        $this->model->delete($mailTemplate);
    }

}
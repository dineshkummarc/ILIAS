<?php
include_once './Services/Calendar/interfaces/interface.ilCalendarAppointmentPresentation.php';
include_once './Services/Calendar/classes/AppointmentPresentation/class.ilAppointmentPresentationGUI.php';

/**
 *
 * @author Jesús López Reyes <lopez@leifos.com>
 * @version $Id$
 *
 * @ilCtrl_IsCalledBy ilAppointmentPresentationGroupGUI: ilCalendarAppointmentPresentationGUI
 *
 * @ingroup ServicesCalendar
 */
class ilAppointmentPresentationGroupGUI extends ilAppointmentPresentationGUI implements ilCalendarAppointmentPresentation
{
    public function collectPropertiesAndActions()
    {
        global $DIC;
        
        include_once "./Modules/Group/classes/class.ilObjGroup.php";

        $settings = ilCalendarSettings::_getInstance();
        $user = $DIC->user();

        $app = $this->appointment;

        $this->lng->loadLanguageModule("grp");

        $cat_info = $this->getCatInfo();

        $grp = new ilObjGroup($cat_info['obj_id'], false);

        $refs = ilObject::_getAllReferences($cat_info['obj_id']);
        $grp_ref_id = current($refs);

        // add common section (title, description, object/calendar, location)
        $this->addCommonSection($app, $cat_info['obj_id']);

        if ($app['event']->isAutogenerated() && $grp->getInformation()) {
            $this->addInfoSection($this->lng->txt("cal_grp_info"));
            $this->addInfoProperty($this->lng->txt("grp_information"), ilUtil::makeClickable(nl2br($grp->getInformation())));
        }

        // last edited
        $this->addLastUpdate($app);

        $this->addAction($this->lng->txt("grp_grp_open"), ilLink::_getStaticLink($grp_ref_id, "grp"));

        // register/unregister to appointment
        if ($settings->isCGRegistrationEnabled()) {
            if (!$app['event']->isAutoGenerated()) {
                include_once './Services/Calendar/classes/class.ilCalendarRegistration.php';
                $reg = new ilCalendarRegistration($app['event']->getEntryId());

                if ($reg->isRegistered($user->getId(), new ilDateTime($app['dstart'], IL_CAL_UNIX), new ilDateTime($app['dend'], IL_CAL_UNIX))) {
                    //$this->ctrl->setParameterByClass('ilcalendarappointmentgui','seed',$this->getSeed()->get(IL_CAL_DATE));
                    $this->ctrl->setParameterByClass('ilcalendarappointmentgui', 'app_id', $app['event']->getEntryId());
                    $this->ctrl->setParameterByClass('ilcalendarappointmentgui', 'dstart', $app['dstart']);
                    $this->ctrl->setParameterByClass('ilcalendarappointmentgui', 'dend', $app['dend']);

                    $this->addAction($this->lng->txt('cal_reg_unregister'), $this->ctrl->getLinkTargetByClass('ilcalendarappointmentgui', 'confirmUnregister'));
                } else {
                    //$this->ctrl->setParameterByClass('ilcalendarappointmentgui','seed',$this->getSeed()->get(IL_CAL_DATE));
                    $this->ctrl->setParameterByClass('ilcalendarappointmentgui', 'app_id', $app['event']->getEntryId());
                    $this->ctrl->setParameterByClass('ilcalendarappointmentgui', 'dstart', $app['dstart']);
                    $this->ctrl->setParameterByClass('ilcalendarappointmentgui', 'dend', $app['dend']);

                    $this->addAction($this->lng->txt('cal_reg_register'), $this->ctrl->getLinkTargetByClass('ilcalendarappointmentgui', 'confirmRegister'));
                }
                $registered = $reg->getRegisteredUsers(
                    new \ilDateTime($app['dstart'], IL_CAL_UNIX),
                    new \ilDateTime($app,'dend', IL_CAL_UNIX)
                );

                $users = "";
                foreach ($registered as $user) {
                    $users .= $this->getUserName($user) . '<br />';
                }
                if ($users != "") {
                    $this->addInfoProperty($this->lng->txt("cal_reg_registered_users"), $users);
                }
            }
        }
    }
}

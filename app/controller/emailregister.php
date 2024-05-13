<?php
Class EmailRegister extends \SENE_Controller{
  public function __construct(){
    parent::__construct();
    $this->lib('seme_email');
  }
  public function index(){
    $this->seme_email->flush();
    $this->seme_email->replyto('Support Example Website', 'support@cenah.co.id');
    $this->seme_email->from('noreply@example.com', 'Adry from Example Website');
    $this->seme_email->subject('Registration Successful');
    $this->seme_email->to('agus.setiawan@example.com', 'Agus Setiawan');
    $this->seme_email->text('Thank you for registering on Example website.');
    $this->seme_email->send();

    // opsinal untuk melihat log dari proses kirim email ini
    dd($this->seme_email->getLog());
  }
}
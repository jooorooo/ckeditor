<?php namespace Simexis\CKEditor;

class CKEditor {

    const TYPE_FULL = 'full';
    const TYPE_STANDARD = 'standard';
    const TYPE_SIMPLE = 'simple';
    const TYPE_INLINE = 'inline';

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $allowedContent;

    /**
     * @var string
     */
    protected $toolbarConfig;

    /**
     * @var array
     */
    protected $filebrowser;

    /**
     * @var string
     */
    protected $replaceByClass;

    /**
     * @var string
     */
    protected $height;

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function config($key) {
        return $this->app['config']->get('ckeditor.' . $key);
    }

    public function setLanguage($language) {
        $this->language = $language;
        return $this;
    }

    public function getLanguage() {
        if(!$this->language)
            $this->setLanguage($this->config('config.language'));
        return $this->language;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getType() {
        if(!$this->type)
            $this->setType($this->config('type'));
        return $this->type;
    }

    public function setToolbarConfig($data) {
        $this->toolbarConfig = $data;
        return $this;
    }

    public function getToolbarConfig() {
        if(!$this->toolbarConfig)
            $this->setToolbarConfig($this->config('toolBarConfig'));
        return $this->toolbarConfig;
    }

    public function setAllowedContent($data) {
        $this->allowedContent = (bool)$data;
        return $this;
    }

    public function getAllowedContent() {
        if(is_null($this->allowedContent))
            $this->setAllowedContent($this->config('config.allowedContent'));
        return $this->allowedContent ? 'true' : 'false';
    }

    public function setFilebrowser($key, $data) {
        $this->filebrowser[$key] = $data;
        return $this;
    }

    public function getFilebrowser($key) {
        if(!isset($this->filebrowser[$key]))
            $this->setFilebrowser($key, $this->config('config.filebrowser' . $key));
        return $this->filebrowser[$key];
    }

    public function setByClass($data) {
        $this->replaceByClass = $data;
        return $this;
    }

    public function getByClass() {
        if(!$this->replaceByClass)
            $this->setByClass($this->config('replaceByClass'));
        return $this->replaceByClass;
    }

    public function setHeight($data) {
        $this->height = (int)$data;
        return $this;
    }

    public function getHeight() {
        if(!$this->height)
            $this->setHeight($this->config('height'));
        return $this->height;
    }

    public function scripts() {

        $script = "
        if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 ) {
	        CKEDITOR.tools.enableHtml5Elements( document );
        }\n";
        if ( $this->getType() != CKEditor::TYPE_INLINE)
        {
            if($this->getByClass()) {
                $script .= "jQuery( '" . $this->getByClass() . "' ).ckeditor();";
            } else {
                $script .= "CKEDITOR.replaceAll(function(textarea, config) {
					config.height = '" . $this->getHeight() . "';
                });";
            }
        }

        $script .= "\nCKEDITOR.config.language = '" . $this->getLanguage() . "';";
        foreach(['BrowseUrl', 'ImageBrowseUrl', 'FlashBrowseUrl', 'UploadUrl', 'ImageUploadUrl', 'FlashUploadUrl'] AS $key) {
            if(!is_null($res = $this->getFilebrowser($key))) {
                $script .= "\nCKEDITOR.config.filebrowser{$key} = '" . $res . "';";
            }
        }
        $script .= "\nCKEDITOR.config.allowedContent = " . $this->getAllowedContent() . ";";

        if ( $this->getToolbarConfig() )
        {
            $script .= $this->getToolbarConfig();
        }
        elseif ( $this->getType() == static::TYPE_SIMPLE )
        {
            $script .= "
				CKEDITOR.config.toolbar = [
					['Maximize','Format','Bold','Italic','Underline','StrikeThrough','RemoveFormat','-','NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Link', 'Unlink']
				] ;
			";
        }
        elseif  ( $this->getType() == static::TYPE_STANDARD )
        {
            $script .= "
				CKEDITOR.config.toolbar = [
					['Maximize', 'Format'],
					['Bold','Italic','Underline','StrikeThrough','RemoveFormat','-','TextColor'],
					['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
					['Image','Table','-','Link', 'Unlink', 'Anchor'], ['Source'],
				] ;
			";
        }
        elseif  ( $this->getType() == static::TYPE_INLINE )
        {
            $script .= "
				CKEDITOR.config.extraPlugins = 'inlinesave';
				CKEDITOR.config.toolbar = [
					['Inlinesave', 'Inlinecancel','Format'],
					['Bold','Italic','Underline','StrikeThrough','RemoveFormat','-','TextColor'],
					['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
					['Image','Table','-','Link', 'Unlink']
				];
			";
        }

        $result = '';
        $result .= "<script src=\"" . preg_replace('~^https?://~i', '//', $this->app['url']->asset('vendor/simexis-ckeditor/ckeditor.js', false)) . "\"></script>\n";
        if($this->getByClass())
            $result .= "<script src=\"" . preg_replace('~^https?://~i', '//', $this->app['url']->asset('vendor/simexis-ckeditor/adapters/jquery.js', false)) . "\"></script>\n";
        $result .= "<script type=\"text/javascript\">\n" . $script . "\n</script>";
        return $result;
    }

}

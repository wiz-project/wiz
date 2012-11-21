<?

/**
 * FileUpload class.
 * FileUpload is the data structure for .xls file
 * It is used by the 'upload' action of 'FileUploadController'.
 */
class FileUpload extends CFormModel {
 
	/**
     * @var string $uploaded_file the name of file to be uploaded
     */
	public $uploaded_file;
		
	/**
	* @return array validation rules for model attributes.
	*/
	public function rules() {
		return array(
			//note you wont need a safe rule here
			array('uploaded_file', 
			      'file', 
				  'allowEmpty' => false, 
				  'types' => 'xls',
				  'maxSize'=>1024 * 1024 * 50, // 50MB
				  'tooLarge'=>'The file was larger than 50MB. Please upload a smaller file.'),
		);
	}
		
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	*/
	public function attributeLabels()
		{
			return array(
				'uploaded_file'=>Yii::t('excel','Filename'),
			);
		}
	}

?>
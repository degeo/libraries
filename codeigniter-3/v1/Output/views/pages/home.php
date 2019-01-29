<h3>Current Features:</h3>
<ul>
	<li>Foundation CSS/JS Framework - Load Foundation with 2 lines of code</li>
	<li>Dynamic Layout System - Allows easy customization and content loading through PHP code</li>
	<li>Breadcrumbs - Styling by Foundation</li>
	<li>Google Analytics Tracking Code - Add the latest Google Analytics Tracking Code with your tracking ID with 2 lines of code</li>
	<li>Dynamic Resource Management System - Allows for simple and optimized resource loading</li>
	<li>Simple SEO Support - Makes it easy to keep titles and meta information consistent which is great for SEO</li>
</ul>

<h3>Example Controller <code>index()</code> Method</h3>
<code>
<pre>
public function index() {
	# Load Degeo Application Model
	$this->load->model('Degeo_app_application_model', 'Degeo_application');
	
	# Start Degeo Application
	$this->Degeo_application->start( 'Degeo App Builder for CodeIgniter', '0.0.1' );
	
	# Load Google Analytics Tracking
	$this->Degeo_google_analytics->load( 'UA-xxxxxxxx-x' );
	
	# Set Meta Description
	$this->Degeo_seo->set_meta_description( 'Description of Degeo App Builder Test Suite' );
	
	# Set Meta Keywords
	$this->Degeo_seo->set_meta_keywords( 'Degeo,App,Builder,Test,Sweet' );
	
	# Set Page Title - Automatically sets  <code>&lt;title&gt;</code> tag
	$this->Degeo_application->set_page_title( 'Deploy A New CodeIgniter App With Foundation CSS/JS In Less Than 5 Minutes' );
	
	# Set body section to a single column layout
	$this->Degeo_layout->add_section( 'body', 'degeo-layouts/1-column', 50 );
	
	# Add content view files
	$this->Degeo_layout->add_content( 'page-title', 'degeo-templates/page-title', 0 );
	$this->Degeo_layout->add_content( 'home-page', 'degeo-pages/home', 10 );
	
	# Load the Layout views
	$this->Degeo_layout->view( $this->data );
} // function
</pre>
</code>
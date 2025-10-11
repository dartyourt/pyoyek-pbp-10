import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Import mini-cart component
import './components/miniCart';

// Import small add-to-cart helper which will wire product forms to AJAX
import './components/addToCart';

Alpine.start();

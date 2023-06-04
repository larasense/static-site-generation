## Active development: Not ready to use

This package is in active development.

## Static Site Generation and Inmidiate Site Reconsiliation

In some cases using all you resources or most of the services in the application infraestructure in every request is unnecessary. In some cases a page need to be generated only one time and it can be done as a deploy step.

Think in a eCommerce site: the landing page is the same for every visitor and the product page it also the same for every consumer.
Now, we are not talking on a static only site like a documentation site, but application that the first render can be done once and only access the Databases or API's only if the user start to interact with it.

## Instalation

```bash
composer require larasense/static-site-generation
```

## How to use it

create a controller

```bash
php artisan make:controller HomeController
```

in the controller define the attributes to SSG or ISR

```php
namespace App\Http\Controller;

use SSG;

class HomeController extends Controller
{

	[#SSG(url:'/')]
	public function index(Request $request)
	{
		// all processing
		// ...
		// and render the page
		return Inertia::render('product/Show', [
			'products'=> $products,
			'promos'  => $promotions,
			'Heros'   => $hero_images,
			// other info that the landing page must show
		]);
	}
}
```

and then use the command to generate the renders

```shell
php artisan SSG:generate
```

add this command to the CI/CD as a deploy step and you are good to go.

### Define path function

Some times the routes have some dynamic parameters. For those cases you can use the path function

In the controller define the attribute with the configuration

```php
namespace App\Http\Controller;

use Josensanchez\LaravelSSG\Attributes;

class ProductController extends Controller
{
	/**
	 *
	 * url /product/{product}
	 */
	 [#SSG(paths: 'getStaticPath')#]
	public function show(Product $product)
	{
		// all processing
		return Inertia::render('product/Show', compact('product'));
	}

	/**
	 * it generate an array like [ 'product'=>1, 'product'=>2, 'product'=>3 ]
	 * and it will use it to generate the routes
	 *  /product/1
	 *  /product/2
	 *  /product/3
	 */
	public static function getStaticPath()
	{
		return Product::where('need_to_be_pre_render', true)
			->get()
			->map(fn ($product) => ['product' => $product->id]
			->toArray();
	}
}
```

## Other configuration

```
[#SSG(
	revalidate: integer,
	paths: String,
)#]
```

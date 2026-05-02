<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ─── MAIN COURSE ─────────────────────────────────────────────
            'Main Course' => [
                [
                    'name'        => 'Nasi Ayam Sambal Matah',
                    'description' => 'Nasi dengan ayam crispy, sambal matah segar, daun kemangi, jeruk, dan full of flavor.',
                    'base_price'  => 33000,
                    'is_featured' => true,
                ],
                [
                    'name'        => 'Nasi Tuna Sambal Kecombrang',
                    'description' => 'Tuna segar dengan sambal kecombrang nusantara, daun kemangi, full of flavor.',
                    'base_price'  => 33000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Nasi Kulit Butter Rice',
                    'description' => 'Nasi butter premium dengan kulit ayam crispy, soy, savory, but not heavy.',
                    'base_price'  => 31000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Namazu Kabayaki',
                    'description' => 'Ikan lele dengan saus kabayaki Jepang, nasi hangat, soy, juicy and satisfying.',
                    'base_price'  => 31000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Bakmi',
                    'description' => 'Mie kuning premium dengan ayam crispy, soy, savory and satisfying.',
                    'base_price'  => 29000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Tori Miso Noodle',
                    'description' => 'Rice miso chicken broth dengan topping chicken dan flat brown noodle.',
                    'base_price'  => 32000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Nasi Goreng',
                    'description' => 'Classic nasi goreng dengan savory seasoning, comforting and full of flavor.',
                    'base_price'  => 29000,
                    'is_featured' => false,
                ],
            ],

            // ─── LITE BITES ──────────────────────────────────────────────
            'Lite Bites' => [
                [
                    'name'        => 'Pou Pou Choco',
                    'description' => 'Fluffy bites with smooth, creamy chocolate, juicy chocolate. Best friend forever!',
                    'base_price'  => 18000,
                    'is_featured' => true,
                ],
                [
                    'name'        => 'Donat',
                    'description' => 'Airy, fluffy traditional donut, topped with a gorgeous fountain of toasted sugar.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Churros',
                    'description' => 'Crispy churros dusted with a smooth, attractive chocolate peanut sauce.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Carai Choco',
                    'description' => 'Tasty caramel cheesecake filled with rich velvet chocolate sauce.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Spicy Garlic Edamame',
                    'description' => 'Steamed edamame with garlic and seasoning for bold juicy flavors.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Siomay Goreng',
                    'description' => 'Crispy fried dumplings with a savory and juicy filling inside.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Chicken Egg Roll',
                    'description' => 'Tasty rolls filled with juicy chicken and crunchy with a bite.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Flower Tofu Nemo',
                    'description' => 'Silky tofu with a soft, flavorful house sauce.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'French Fries',
                    'description' => 'Crispy, salty and satisfying golden french fries.',
                    'base_price'  => 16000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Garage Platter',
                    'description' => 'A tasty mix of snacks bites, perfect for sharing. A serving ok.',
                    'base_price'  => 31000,
                    'is_featured' => true,
                ],
            ],

            // ─── ESKOSU ──────────────────────────────────────────────────
            'Eskosu' => [
                [
                    'name'        => 'Texdc Coffee Ice',
                    'description' => 'Unique coffee experience that keeps your soul, body and atmosphere alive.',
                    'base_price'  => 22000,
                    'is_featured' => true,
                ],
                [
                    'name'        => 'Garage Coffee Ice',
                    'description' => 'Our signature coffee with a crisp edge added above, hard to fit.',
                    'base_price'  => 22000,
                    'is_featured' => true,
                ],
                [
                    'name'        => 'Butterscotch Ice',
                    'description' => 'Buttery smooth butterscotch milk espresso, full, tantasy in a sip.',
                    'base_price'  => 25000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Double Shaken Espresso Ice',
                    'description' => 'Lemon shaken espresso with full blends, plum synergy, and chilled.',
                    'base_price'  => 27000,
                    'is_featured' => false,
                ],
            ],

            // ─── BASIC COFFEE ─────────────────────────────────────────────
            'Basic Coffee' => [
                [
                    'name'        => 'Americano Hot or Ice',
                    'description' => 'Simply our espresso with a smooth and refreshing brew.',
                    'base_price'  => 15000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Cafe Latte Hot or Ice',
                    'description' => 'Beautifully lifted espresso with smooth, cafe easy.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'On The Rock Ice',
                    'description' => 'First timeless served espresso poured into brewed bold and chill.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
            ],

            // ─── MIXOLOGY ─────────────────────────────────────────────────
            'Mixology' => [
                [
                    'name'        => 'Lemon Rock Ice',
                    'description' => 'Lemon mineral espresso fresh soda, bold and refreshing.',
                    'base_price'  => 19000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Crampresso Ice',
                    'description' => 'Harmonious espresso in a luxury fusion.',
                    'base_price'  => 22000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Acapella Ice',
                    'description' => 'Lemon mixer blend espresso soda, free, savory, and smooth.',
                    'base_price'  => 19000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Just Mine',
                    'description' => 'Smooth coffee infused with delicate jasmine aroma, light and smooth.',
                    'base_price'  => 22000,
                    'is_featured' => true,
                ],
                [
                    'name'        => 'Tripple Peach',
                    'description' => 'Elderflower ambrosious infused with watery, juicy peach and a smooth airy peachy flavor.',
                    'base_price'  => 22000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Green Canyon',
                    'description' => 'Struck-cool cola with a grassy, savory, and super extraordinary taste.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
            ],

            // ─── VARIANT (TEA) ────────────────────────────────────────────
            'Variant' => [
                [
                    'name'        => 'Berry Burst Hot or Ice',
                    'description' => 'Vibrant tea blended with strawberry for a robby and icy academy energy feeling.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Padle Pop Hot or Ice',
                    'description' => 'Fun, slushy palm with a refreshing and flavorful twist.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Matcha Hot or Ice',
                    'description' => 'Earthy and intense premium matcha feel calm in a cup.',
                    'base_price'  => 22000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Chocolate Hot or Ice',
                    'description' => 'With thick richness with healthy subtleness.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Peach Tea Hot or Ice',
                    'description' => 'Lively and peachy with a refreshing fruit peach harmony in golden joy.',
                    'base_price'  => 16000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Mango Tea Hot or Ice',
                    'description' => 'Tropical, bright and juicy mango with smooth slim juice.',
                    'base_price'  => 16000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Lychee Tea Hot or Ice',
                    'description' => 'Gorgeous lychee with lucky, sunny syringa for a forever not-overwhelming taste.',
                    'base_price'  => 18000,
                    'is_featured' => false,
                ],
                [
                    'name'        => 'Lemon Tea Hot or Ice',
                    'description' => 'Classic light tea with a hint of bright lemon for a light refreshing sip.',
                    'base_price'  => 15000,
                    'is_featured' => false,
                ],
            ],
        ];

        foreach ($data as $categoryName => $products) {
            $category = Category::where('name', $categoryName)->first();
            if (!$category) continue;

            foreach ($products as $p) {
                Product::updateOrCreate(
                    ['slug' => Str::slug($p['name']) . '-' . Str::slug($categoryName)],
                    [
                        'category_id'  => $category->id,
                        'name'         => $p['name'],
                        'description'  => $p['description'],
                        'base_price'   => $p['base_price'],
                        'is_available' => true,
                        'is_featured'  => $p['is_featured'],
                        'allergen_info'=> null,
                        'image'        => null,
                    ]
                );
            }
        }
    }
}

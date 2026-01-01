<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurar que existe al menos un usuario
        $user = User::first() ?? User::factory()->create([
            'name' => 'Redactor Principal',
            'email' => 'redactor@revista.com',
        ]);

        $articles = [
            [
                'title' => 'Fortaleza de San Fernando de Omoa: Guardiana del Caribe Hondureño',
                'subtitle' => 'Una joya colonial que resiste el paso del tiempo en las costas de Honduras',
                'attribution' => 'Ana Martínez',
                'summary' => 'Descubre la majestuosa fortaleza colonial española que protegió las costas del Caribe durante siglos, ahora convertida en un tesoro histórico y turístico de Honduras.',
                'section' => 'destinations',
                'image_path' => 'articles/fortaleza-omoa.jpg',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'content' => 'La Fortaleza de San Fernando de Omoa se alza imponente frente al mar Caribe, como un testigo silencioso de la rica historia colonial de Honduras. Construida en el siglo XVIII por los españoles, esta estructura defensiva se ha convertido en uno de los destinos turísticos más fascinantes del país.'
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'Sus gruesos muros de piedra caliza, extraída de las canteras locales, han resistido huracanes, batallas y el inexorable paso del tiempo. Caminar por sus corredores es como viajar al pasado, cuando piratas y corsarios amenazaban las rutas comerciales del Nuevo Mundo.'
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'Hoy en día, la fortaleza alberga un museo que narra la historia colonial de la región, y sus alrededores ofrecen playas de arena negra volcánica y una vista espectacular del océano Pacífico.'
                    ]
                ],
                'tags' => ['honduras', 'historia', 'colonial', 'caribe', 'turismo', 'fortaleza'],
                'reading_time' => 12,
                'meta_description' => 'Explora la Fortaleza de San Fernando de Omoa, una joya colonial del Caribe hondureño con siglos de historia por descubrir.'
            ],
            [
                'title' => 'Costa Brava: El Encanto Mediterráneo de Girona',
                'subtitle' => 'Calas cristalinas y pueblos pintorescos en la joya de Cataluña',
                'attribution' => 'María Fernández',
                'summary' => 'Un recorrido por la espectacular Costa Brava, donde el Mediterráneo muestra su cara más seductora entre acantilados, calas escondidas y pueblos de postal.',
                'section' => 'destinations',
                'image_path' => 'articles/costa-brava-girona.jpg',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'content' => 'La Costa Brava de Girona es un regalo de la naturaleza donde el azul intenso del Mediterráneo se funde con los verdes pinos que crecen hasta el borde mismo del acantilado. Esta región de Cataluña ofrece una experiencia única que combina paisajes naturales impresionantes con un rico patrimonio cultural.'
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'Desde las famosas calas de Begur hasta los pintorescos pueblos pesqueros como Cadaqués, cada rincón de la Costa Brava cuenta una historia. Las aguas cristalinas invitan al buceo y la navegación, mientras que los senderos costeros ofrecen vistas panorámicas incomparables.'
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'La gastronomía local, con sus arroces marineros y pescados frescos, completa una experiencia sensorial que convierte a la Costa Brava en un destino imprescindible del Mediterráneo español.'
                    ]
                ],
                'tags' => ['costa-brava', 'girona', 'mediterráneo', 'españa', 'turismo', 'playas'],
                'reading_time' => 8,
                'meta_description' => 'Descubre la magia de la Costa Brava en Girona: calas cristalinas, pueblos encantadores y la esencia del Mediterráneo español.'
            ],
            [
                'title' => 'Nueva York: Donde el Mundo se Encuentra en un Plato',
                'subtitle' => 'Un viaje culinario por la diversidad gastronómica de la Gran Manzana',
                'attribution' => 'Diego Rodríguez',
                'summary' => 'Explora cómo Nueva York se ha convertido en el epicentro mundial de la gastronomía, donde cada barrio cuenta su historia a través de sabores únicos.',
                'section' => 'gastronomy',
                'image_path' => 'articles/nueva-york-gastronomia.jpg',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'content' => 'Nueva York es mucho más que rascacielos y luces de neón; es un crisol gastronómico donde convergen todas las tradiciones culinarias del mundo. En esta metrópolis, un simple paseo puede llevarte desde una auténtica pizzería italiana en Little Italy hasta un restaurante de dim sum en Chinatown.'
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'Los food trucks que recorren las calles ofrecen desde tacos mexicanos hasta falafel del Medio Oriente, mientras que los mercados como el Chelsea Market se han convertido en verdaderas catedrales del sabor donde conviven chefs estrella Michelin con vendedores tradicionales.'
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'La diversidad de Nueva York se refleja perfectamente en su escena gastronómica: cada comunidad de inmigrantes ha aportado sus recetas ancestrales, creando una sinfonía de sabores que hace de comer en Nueva York una experiencia transcultural única.'
                    ]
                ],
                'tags' => ['nueva-york', 'gastronomía', 'diversidad', 'cultura', 'inmigración', 'cocina-internacional'],
                'reading_time' => 10,
                'meta_description' => 'Descubre la increíble diversidad gastronómica de Nueva York, donde cada plato cuenta la historia de una cultura diferente.'
            ],
            [
                'title' => 'Encontrando el Equilibrio en Tiempos Acelerados',
                'subtitle' => 'Estrategias de mindfulness para navegar el ritmo de la vida moderna',
                'attribution' => 'Carmen Silva',
                'summary' => 'En un mundo que no para, descubre cómo encontrar momentos de calma y equilibrio personal a través de prácticas milenarias adaptadas a la vida contemporánea.',
                'section' => 'health_wellness',
                'image_path' => 'articles/equilibrio-mindfulness.jpg',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'content' => 'Vivimos en una era de constante aceleración donde la tecnología, las responsabilidades laborales y las demandas sociales parecen empujarnos hacia un ritmo insostenible. Sin embargo, encontrar el equilibrio no es un lujo, sino una necesidad fundamental para nuestro bienestar físico y mental.'
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'Las prácticas de mindfulness y meditación, que durante milenios han ayudado a las personas a encontrar la calma interior, hoy más que nunca cobran relevancia. No se trata de escapar de la realidad, sino de desarrollar la capacidad de estar presentes en medio del caos.'
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'Pequeños rituales diarios como la respiración consciente, la meditación de cinco minutos o simplemente caminar sin prisa pueden transformar radicalmente nuestra experiencia de vida, ayudándonos a recuperar el control y la serenidad en tiempos turbulentos.'
                    ]
                ],
                'tags' => ['mindfulness', 'bienestar', 'equilibrio', 'meditación', 'salud-mental', 'vida-moderna'],
                'reading_time' => 7,
                'meta_description' => 'Aprende estrategias efectivas de mindfulness para encontrar equilibrio y calma en el acelerado ritmo de la vida moderna.'
            ],
            [
                'title' => 'Sabores de Honduras: Tradición en Cada Bocado',
                'subtitle' => 'Un recorrido por la rica gastronomía hondureña y sus raíces ancestrales',
                'attribution' => 'Diego Hernández',
                'summary' => 'Descubre la riqueza culinaria de Honduras, donde la tradición maya, española e indígena se funden en platos llenos de historia y sabor.',
                'section' => 'gastronomy',
                'image_path' => 'articles/sabores-honduras.jpg',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'content' => 'La gastronomía hondureña es un testimonio vivo de la rica historia del país, donde las tradiciones culinarias prehispánicas se fusionaron con las influencias españolas y africanas para crear una identidad gastronómica única en Centroamérica.'
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'Platos emblemáticos como la baleada, las pupusas, el pollo chuco y los tamales navideños no son solo alimentos, sino portadores de memoria cultural. Cada receta transmitida de generación en generación cuenta la historia de un pueblo que ha sabido preservar sus raíces culinarias.'
                    ],
                    [
                        'type' => 'paragraph',
                        'content' => 'Los mercados locales rebosan de ingredientes autóctonos como el maíz criollo, los frijoles rojos, el queso fresco y las verduras tropicales que dan vida a una cocina rica en sabores, aromas y texturas que reflejan la diversidad geográfica y cultural del país.'
                    ]
                ],
                'tags' => ['honduras', 'gastronomía', 'tradición', 'cultura', 'cocina-tradicional', 'centroamérica'],
                'reading_time' => 9,
                'meta_description' => 'Explora la rica tradición gastronómica de Honduras, donde cada plato cuenta la historia ancestral del país centroamericano.'
            ]
        ];

        foreach ($articles as $articleData) {
            Article::create([
                'title' => $articleData['title'],
                'subtitle' => $articleData['subtitle'],
                'attribution' => $articleData['attribution'],
                'summary' => $articleData['summary'],
                'slug' => Str::slug($articleData['title']),
                'image_path' => $articleData['image_path'],
                'visibility' => 'public',
                'status' => 'published',
                'published_at' => now()->subDays(rand(1, 30)),
                'section' => $articleData['section'],
                'tags' => $articleData['tags'],
                'content' => $articleData['content'],
                'view_count' => rand(50, 500),
                'reading_time' => $articleData['reading_time'],
                'meta_description' => $articleData['meta_description'],
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('✅ Se han creado ' . count($articles) . ' artículos exitosamente');
    }
}
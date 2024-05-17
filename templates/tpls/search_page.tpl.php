<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../database/item_class.php');
  require_once(__DIR__ . '/../session.php');

?>

<?php function drawDepartmentPage(Department $department, Session $session) { ?>
  <main>
    <nav class="category-bar">
            <ul>
                <li class="pink-highlight"><a href="women_section.php">Women</a></li> 
                <li><a href="men_section.php">Men</a></li> 
                <li><a href="kids_section.php">Kids</a></li> 
                <li><a href="bags_section.php">Bags</a></li> 
                <li><a href="jewelry_section.php">Jewelry</a></li> 
                <li><a href="accessories_section.html">Accessories</a></li> 
            </ul>
        </nav> 


        <aside class="sorter-sidebar">
            <h2>Sort By</h2>
            <form id="sorters">
                <label for="sort-price">Price:</label>
                <select id="sort-price" onchange="sortProducts();">
                    <option value="default">--</option>
                    <option value="low-to-high">Low to High</option>
                    <option value="high-to-low">High to Low</option>
                </select>
            </form>
        </aside>
        
        <aside class="filter-sidebar">
            <h2>Filter By</h2>
            <form id="filters" method="GET">
    
    <fieldset>
        <legend>Category</legend>
        <label><input type="checkbox" value="Dresses" name="category[]">Dresses</label>
        <label><input type="checkbox" value="Coats" name="category[]">Coats</label>
        <label><input type="checkbox" value="Shoes" name="category[]">Shoes</label>
        <label><input type="checkbox" value="Jeans" name="category[]">Jeans</label>
        <label><input type="checkbox" value="Pants" name="category[]">Pants</label>
        <label><input type="checkbox" value="Shorts" name="category[]">Shorts</label>
        <label><input type="checkbox" value="Skirts" name="category[]">Skirts</label>
        <label><input type="checkbox" value="Swimwear" name="category[]">Swimwear</label>
        <label><input type="checkbox" value="Tops" name="category[]">Tops</label>
    </fieldset>

            
    
                <fieldset>
                    <legend>Subcategory</legend>
                    <div id="subcategory-container">
                        <!-- Subcategories will be populated here -->
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Condition</legend>
                    <div id="condition-container">
                        <label><input type="checkbox" name="condition[]" value="Excellent">Excellent</label>
                        <label><input type="checkbox" name="condition[]" value="Very good">Very good</label>
                        <label><input type="checkbox" name="condition[]" value="Good">Good</label>
                        <label><input type="checkbox" name="condition[]" value="Bad">Bad</label>
                    </div>
                </fieldset>

        
                <fieldset>
                    <legend>Size</legend>
                    <label><input type="checkbox" name="size[]" value="XS">XS</label>
                    <label><input type="checkbox" name="size[]" value="S">S</label>
                    <label><input type="checkbox" name="size[]" value="M">M</label>
                    <label><input type="checkbox" name="size[]" value="L">L</label>
                    <label><input type="checkbox" name="size[]" value="XL">XL</label>
                </fieldset>

        
    
                <label for="min-price">Min Price:</label>
                <input type="text" id="min-price" name="min_price" placeholder="Min Price">

                <label for="max-price">Max Price:</label>
                <input type="text" id="max-price" name="max_price" placeholder="Max Price">

                <button type="button" class="reset" onclick="resetFilters()">Reset Filters</button>
                <button type="submit">Apply Filters</button>
            </form>
           

        </aside>

        <div class="products">
          <?php 
          foreach ($items as $item):
              // Assuming you have a seller_id and need to fetch the seller's username for each item
              $seller_username_stmt = $pdo->prepare("SELECT username FROM User WHERE id = ?");
              $seller_username_stmt->execute([$item['seller_id']]);
              $seller_username = $seller_username_stmt->fetchColumn();

              // If no username was found, use a placeholder or empty string
              $seller_username = $seller_username ?: 'Unknown';  // Default to 'Unknown' if no username is found
              $image_url = "../images/items/item{$item['id']}_1.png";
          ?>
              <a href="product_page.php?product_id=<?php echo htmlspecialchars($item['id']); ?>" class="product-link" data-product-id="<?php echo htmlspecialchars($item['id']); ?>">
                  <div class="product">
                      <p>@<?php echo htmlspecialchars($seller_username); ?></p>
                      <h3><?php echo htmlspecialchars($item['title'] ?? 'No title available'); ?></h3>
                      <div class="image-container">
                          <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($item['title'] ?? 'No title available'); ?>">
                      </div>
                      <p>€<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></p>
                      <p>Size <?php echo htmlspecialchars($item['item_size'] ?? 'N/A'); ?></p>
                  </div>
              </a>
          <?php 
          endforeach; 
          ?>
        </div>

        <div class="pagination">
                <button onclick="changePage(-1)">Prev</button>
                <span id="pageNumber">1</span>
                <button onclick="changePage(1)">Next</button>
        </div>
  </main>

  <script>
       function updateSubcategories() {
        var categoryCheckboxes = document.querySelectorAll('input[name="category[]"]:checked');
        var selectedCategories = Array.from(categoryCheckboxes).map(cb => cb.value);
        var subcategoryContainer = document.getElementById('subcategory-container');
        subcategoryContainer.innerHTML = ''; // Clear previous subcategory options

        var options = {
            'Dresses': ['Mini', 'Midi', 'Maxi'],
            'Coats': ['Winter', 'Summer', 'Raincoat'],
            'Shoes': ['Sneakers', 'Boots', 'Sandals', 'Heels'],
            'Jeans': ['Loose Fit', 'Skinny Fit', 'Bootcut'],
            'Skirts': ['Mini', 'Midi', 'Maxi'],
            'Shorts': ['Short length', 'Mid length'],
            'Tops': ['T-shirts', 'Blouses', 'Crop tops', 'Shirts'],
            'Pants': ['Loose Fit', 'Skinny Fit', 'Bootcut'],
            'Swimwear': ['Bikini', 'One-piece']
        };

        selectedCategories.forEach(category => {
            if (options[category]) {
                options[category].forEach(sub => {
                    var label = document.createElement('label');
                    var checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.value = sub;
                    checkbox.name = 'subcategory[]';
                    label.appendChild(checkbox);
                    label.append(sub);
                    subcategoryContainer.appendChild(label);
                });
            }
        });
      }

    document.addEventListener('DOMContentLoaded', function() {
        var categoryCheckboxes = document.querySelectorAll('input[name="category[]"]');
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSubcategories);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var categoryCheckboxes = document.querySelectorAll('input[name="category"]');
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSubcategories);
        });
        updateSubcategories();
    });

        </script>

        <script>
            const productsPerPage = 9;
            let currentPage = 1;
        
            function paginateProducts() {
                const products = document.querySelectorAll('.product');
                const totalProducts = products.length;
                const totalPages = Math.ceil(totalProducts / productsPerPage);
        
                products.forEach(product => {
                    product.style.display = 'none';
                });
        
                
                const startIndex = (currentPage - 1) * productsPerPage;
                const endIndex = startIndex + productsPerPage;
        
                
                for (let i = startIndex; i < endIndex && i < totalProducts; i++) {
                    products[i].style.display = 'block';
                }
        
                // Mostra o número correto da página
                document.getElementById('pageNumber').textContent = `${currentPage} / ${totalPages}`;
            }
        
            function changePage(step) {
                const products = document.querySelectorAll('.product');
                const totalProducts = products.length;
                const totalPages = Math.ceil(totalProducts / productsPerPage);
        
                // Atualiza página
                currentPage += step;
                if (currentPage < 1) {
                    currentPage = 1;
                } else if (currentPage > totalPages) {
                    currentPage = totalPages;
                }
        
                paginateProducts();
            }
        
            document.addEventListener('DOMContentLoaded', function() {
                updateSubcategories();
                paginateProducts(); 
            });
  </script>

  <script>

    function resetFilters() {
        // Uncheck all checkboxes
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        // Clear text inputs
        document.querySelectorAll('input[type="text"]').forEach(input => {
            input.value = '';
        });

        // Submit the form
        document.getElementById('filters').submit();
    }


    document.addEventListener('DOMContentLoaded', function() {
        const productLinks = document.querySelectorAll('.product-link');
        productLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const productId = this.getAttribute('data-product-id');
                window.location.href = `product_page.php?product_id=${productId}`;
            });
        });
    });


    function sortProducts() {
        var sortBy = document.getElementById('sort-price').value;
        var container = document.querySelector('.products');
        var products = Array.from(container.querySelectorAll('.product'));

        products.sort(function(a, b) {
            // Correctly parse prices as floats
            var priceA = parseFloat(a.querySelector('p:nth-last-child(2)').textContent.replace(/[^\d,.]/g, '').replace(',', '.'));
            var priceB = parseFloat(b.querySelector('p:nth-last-child(2)').textContent.replace(/[^\d,.]/g, '').replace(',', '.'));

            if (sortBy === 'low-to-high') {
                return priceA - priceB;
            } else if (sortBy === 'high-to-low') {
                return priceB - priceA;
            }
            return 0;
        });

        // Re-append sorted products
        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }

        products.forEach(function(product) {
            container.appendChild(product);
        });
    }

    // Function to toggle the dropdown menu
    function toggleProfileDropdown() {
            const dropdownContainer = document.querySelector('.profile-dropdown');
            dropdownContainer.classList.toggle('show');
    }

        // Event listener for clicking the profile icon
    document.getElementById('profile-icon').addEventListener('click', function (event) {
            event.stopPropagation(); 
            toggleProfileDropdown();
    });

        // Event listener for clicking outside the dropdown to close it
    window.addEventListener('click', function () {
            const dropdownContainer = document.querySelector('.profile-dropdown');
            if (dropdownContainer.classList.contains('show')) {
                dropdownContainer.classList.remove('show');
            }
    });

  </script>

  </body>
  </html>
<?php } ?>


<?php function drawSearchPageHead(Session $session) { ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Women's Section - Elite Finds</title>
        <link rel="stylesheet" href="../css/women_section.css">
    </head>
    <body>
<?php } ?>



<?php function drawAlbum(Album $album, Artist $artist, array $tracks, Session $session) { ?>
  <h2><?=$album->title?>
    <?php if($session->isLoggedIn()) {?>
      <a href="../pages/edit_album.php?id=<?=$album->id?>"><i class="fa-solid fa-pen action"></i></a>
    <?php } ?>
  </h2>
  <h3><a href="../pages/artist.php?id=<?=$artist->id?>"><?=$artist->name?></a></h3>      
  <table id="tracks">
    <tr><th scope="col">#</th><th scope="col">Title</th><th scope="col">Duration</th></tr>
    <?php foreach ($tracks as $id => $track) { ?>
      <tr><td><?=$id + 1?></td><td><?=$track->name?></td><td><?=$track->time()?></td></tr>
    <?php } ?>
  </table>
<?php } ?>
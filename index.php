<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP AJAX Load More Products</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            background-color: #f9fafb;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #111827;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
        }

        .card img {
            width: 100%;
            height: 160px;
            object-fit: contain;
            margin-bottom: 12px;
        }

        .card h4 {
            margin: 8px 0;
            font-size: 1rem;
            color: #1f2937;
        }

        .category {
            font-size: 0.8rem;
            color: #6b7280;
            text-transform: capitalize;
        }

        .price {
            font-weight: bold;
            color: #16a34a;
            font-size: 1.15rem;
            margin-top: 8px;
        }

        .btn-wrap {
            text-align: center;
            margin: 40px 0;
        }

        button {
            padding: 12px 28px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            background: #111827;
            color: #fff;
            border: none;
            border-radius: 8px;
            transition: background 0.2s;
        }

        button:hover:not(:disabled) {
            background: #374151;
        }

        button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <h2>Featured Products</h2>
    <div id="product-grid" class="grid"></div>

    <div class="btn-wrap">
        <button id="load-more-btn" onclick="loadProducts()">Load More</button>
    </div>

    <script>
        let skip = 0;
        const limit = 4;
        const grid = document.getElementById('product-grid');
        const btn = document.getElementById('load-more-btn');

        async function loadProducts() {
            btn.disabled = true;
            btn.textContent = 'Loading...';

            try {
                const res = await fetch(`api.php?limit=${limit}&skip=${skip}`);
                const data = await res.json();

                if (data.error) {
                    throw new Error(data.details || data.error);
                }

                data.products.forEach(product => {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.innerHTML = `
            <img src="${product.thumbnail}" alt="${product.title}">
            <div class="category">${product.category}</div>
            <h4>${product.title}</h4>
            <p class="price">$${product.price}</p>
          `;
                    grid.appendChild(card);
                });

                skip += limit;

                if (skip >= data.total) {
                    btn.style.display = 'none';
                } else {
                    btn.disabled = false;
                    btn.textContent = 'Load More';
                }
            } catch (err) {
                console.error(err);
                btn.textContent = 'Failed to load. Try Again';
                btn.disabled = false;
            }
        }

        // Page load hote hi pehle 4 products fetch karega
        loadProducts();
    </script>

</body>

</html>
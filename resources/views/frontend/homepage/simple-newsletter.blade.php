<!-- Simple Newsletter Section -->
<section class="simple-newsletter-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="newsletter-content text-center">
                    <h2 class="newsletter-title">Stay Updated</h2>
                    <p class="newsletter-subtitle">Subscribe to our newsletter for the latest updates and offers</p>
                    <form class="newsletter-form">
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Enter your email address" required>
                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.simple-newsletter-section {
    padding: 60px 0;
    background: linear-gradient(135deg, #242424 0%, #354b65 100%);
    color: white;
}

.newsletter-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: white;
}

.newsletter-subtitle {
    font-size: 1.1rem;
    margin-bottom: 30px;
    opacity: 0.9;
}

.newsletter-form {
    max-width: 500px;
    margin: 0 auto;
}

.form-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.form-control {
    flex: 1;
    padding: 12px 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    min-width: 200px;
}

.form-control:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 99, 209, 0.2);
}

.btn {
    padding: 12px 25px;
    background: #0063d1;
    color: white;
    border: none;
    border-radius: 5px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn:hover {
    background: #0052b3;
}

@media (max-width: 768px) {
    .newsletter-title {
        font-size: 2rem;
    }
    
    .form-group {
        flex-direction: column;
    }
    
    .form-control {
        min-width: auto;
    }
}
</style>

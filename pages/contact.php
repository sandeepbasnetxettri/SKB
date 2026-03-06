<?php
$basePath = "../";
$pageTitle = "Contact Us";
$headerClass = "scrolled";
$extraStyles = '
    <style>
        .contact-page {
            padding: 15rem 0;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 8rem;
        }

        .contact-info-list {
            margin-top: 3rem;
        }

        .contact-info-item {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .contact-info-item ion-icon {
            font-size: 1.5rem;
            color: var(--color-accent);
        }

        .contact-info-item h4 {
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.1rem;
        }

        .contact-form-group {
            margin-bottom: 2rem;
        }

        .contact-form-group label {
            display: block;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.1rem;
            margin-bottom: 0.5rem;
        }

        .contact-form-group input,
        .contact-form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--color-border);
            font-family: inherit;
            outline: none;
            transition: var(--transition-fast);
        }

        .contact-form-group input:focus,
        .contact-form-group textarea:focus {
            border-color: var(--color-primary);
        }
    </style>
';
include $basePath . 'includes/header.php';
?>

    <main class="contact-page">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-sidebar">
                    <h1 style="font-size: 3.5rem; margin-bottom: 2rem;">Get in Touch</h1>
                    <p style="color: var(--color-text-muted);">Have questions about our collection or your order? Our
                        team is here to help you.</p>

                    <div class="contact-info-list">
                        <div class="contact-info-item">
                            <ion-icon name="location-outline"></ion-icon>
                            <div>
                                <h4>Our Studio</h4>
                                <p>123 Minimalist Way, Fashion District<br>New York, NY 10001</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <ion-icon name="mail-outline"></ion-icon>
                            <div>
                                <h4>Email Us</h4>
                                <p>hello@vesture.com<br>support@vesture.com</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <ion-icon name="call-outline"></ion-icon>
                            <div>
                                <h4>Call Us</h4>
                                <p>+1 (555) 000-1234</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-form-wrapper">
                    <form onsubmit="event.preventDefault(); alert('Message sent successfully!'); this.reset();">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                            <div class="contact-form-group">
                                <label>Name</label>
                                <input type="text" placeholder="John Doe" required>
                            </div>
                            <div class="contact-form-group">
                                <label>Email</label>
                                <input type="email" placeholder="john@example.com" required>
                            </div>
                        </div>
                        <div class="contact-form-group">
                            <label>Subject</label>
                            <input type="text" placeholder="Inquiry about..." required>
                        </div>
                        <div class="contact-form-group">
                            <label>Message</label>
                            <textarea rows="6" placeholder="Your message here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

<?php include $basePath . 'includes/footer.php'; ?>

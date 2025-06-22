@props(['url', 'title', 'description', 'image' => null])

<div class="social-share-container">
    <h5 class="share-title">Share This</h5>
    <div class="social-share-buttons">
        <!-- Facebook Share -->
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}" 
           target="_blank" 
           class="social-share-btn facebook-share"
           aria-label="Share on Facebook">
            <i class="fab fa-facebook-f"></i>
        </a>
        
        <!-- Twitter/X Share -->
        <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}&text={{ urlencode($title) }}" 
           target="_blank" 
           class="social-share-btn twitter-share"
           aria-label="Share on Twitter">
            <i class="fab fa-twitter"></i>
        </a>
        
        <!-- LinkedIn Share -->
        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($url) }}&title={{ urlencode($title) }}&summary={{ urlencode($description) }}" 
           target="_blank" 
           class="social-share-btn linkedin-share"
           aria-label="Share on LinkedIn">
            <i class="fab fa-linkedin-in"></i>
        </a>
        
        <!-- WhatsApp Share -->
        <a href="https://wa.me/?text={{ urlencode($title . ' ' . $url) }}" 
           target="_blank" 
           class="social-share-btn whatsapp-share"
           aria-label="Share on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        
        <!-- Telegram Share -->
        <a href="https://t.me/share/url?url={{ urlencode($url) }}&text={{ urlencode($title) }}" 
           target="_blank" 
           class="social-share-btn telegram-share"
           aria-label="Share on Telegram">
            <i class="fab fa-telegram-plane"></i>
        </a>
        
        <!-- Email Share -->
        <a href="mailto:?subject={{ urlencode($title) }}&body={{ urlencode($description . '\n\n' . $url) }}" 
           class="social-share-btn email-share"
           aria-label="Share via Email">
            <i class="fas fa-envelope"></i>
        </a>
        
        <!-- Copy Link Button -->
        <button onclick="copyToClipboard('{{ $url }}')" 
                class="social-share-btn copy-link"
                aria-label="Copy link to clipboard">
            <i class="fas fa-link"></i>
        </button>
    </div>
</div>

<!-- Copy to clipboard functionality -->
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show toast or notification
        const toast = document.createElement('div');
        toast.className = 'copy-toast';
        toast.textContent = 'Link copied to clipboard!';
        document.body.appendChild(toast);
        
        // Remove toast after animation
        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 500);
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>

<style>
.social-share-container {
    margin: 30px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.share-title {
    margin-bottom: 15px;
    color: #333;
    font-size: 18px;
    font-weight: 600;
}

.social-share-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.social-share-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: #fff;
    font-size: 18px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.social-share-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.facebook-share {
    background-color: #3b5998;
}

.twitter-share {
    background-color: #1DA1F2;
}

.linkedin-share {
    background-color: #0077b5;
}

.whatsapp-share {
    background-color: #25D366;
}

.telegram-share {
    background-color: #0088cc;
}

.email-share {
    background-color: #777;
}

.copy-link {
    background-color: #6c757d;
    border: none;
    cursor: pointer;
}

/* Toast notification styling */
.copy-toast {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    color: #fff;
    padding: 10px 20px;
    border-radius: 4px;
    z-index: 9999;
    animation: fade-in 0.3s ease;
}

.copy-toast.fade-out {
    animation: fade-out 0.5s ease forwards;
}

@keyframes fade-in {
    from { opacity: 0; bottom: 20px; }
    to { opacity: 1; bottom: 30px; }
}

@keyframes fade-out {
    from { opacity: 1; }
    to { opacity: 0; }
}

@media (max-width: 576px) {
    .social-share-buttons {
        justify-content: center;
    }
}
</style>

<style>
    /* Tambahan Interaktivitas untuk Footer */
    .footer-col h4 {
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }

    /* Efek garis bawah pada judul saat di-hover */
    .footer-col h4::after {
        content: '';
        display: block;
        width: 0;
        height: 2px;
        background: #5b42f3;
        transition: width 0.3s ease;
    }
    .footer-col:hover h4::after { width: 100%; }

    /* Efek hover pada tombol sosial media */
    .social-circle {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .social-circle:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 5px 15px rgba(91, 66, 243, 0.4);
    }

    /* Efek hover pada nama anggota tim */
    .contact-names p {
        transition: transform 0.2s ease;
    }
    .contact-names p:hover {
        transform: translateX(5px);
    }
    .contact-names a {
        transition: color 0.3s ease;
    }
    .contact-names a:hover {
        color: #5b42f3 !important;
    }

    /* Efek shadow pada Maps */
    .map-wrapper {
        border-radius: 8px;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }
    .map-wrapper:hover {
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
    }
</style>

<footer class="footer">
        <div class="footer-content">
            <div class="footer-col about-col">
                <h4>Tentang Kami</h4>
                <p>SIKOSUB (Sistem Informasi Kost Subang) Platform Resmi untuk mencari info lokasi, harga Kost disekitar Subang Kota yang dicari Berdasarkan Lokasi, harga Dan Fasilitas</p>
                <div class="social-icons">
                    <a href="https://wa.me/62838279144570?text=Halo%20Admin%20SIKOSUB,%20saya%20ingin%20bertanya%20mengenai%20info%20kost." class="social-circle" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WA">
                    </a>
                    <a href="https://www.tiktok.com/@arifamrlh27?_r=1&_t=ZS-973SKZqJtC9" class="social-circle" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/en/a/a9/TikTok_logo.svg" alt="TikTok">
                    </a>
                    <a href="https://www.instagram.com/arif_amrullah275?igsh=MW1seXFmanM5eHRzYg==" class="social-circle" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="IG">
                    </a>
                </div>
            </div>
            
            <div class="footer-col contact-col">
                <h4>Hubungi</h4>
                <div class="contact-names">
                    <p><a href="https://www.instagram.com/arif_amrullah275?igsh=MW1seXFmanM5eHRzYg==" target="_blank">MUHAMMAD ARIF AMRULLAH</a></p>
                    <p><a href="https://www.instagram.com/zeerahizq_?igsh=OXFmcWczbjBzNW9h" target="_blank">HILDAN FAUZIRAHMAN HIZBUL HAQ</a></p>
                    <p><a href="https://www.instagram.com/arrxfinly_a_s_?igsh=MTh0aWlxYnMzMWdmOQ==" target="_blank">ARRAFLY AZIZ SAPUTRA</a></p>
                    <p><a href="https://www.instagram.com/ardi_air?igsh=ZXlqc2I1enB1c3Ft" target="_blank">ARDI ILAHI ROBY</a></p>
                    <p><a href="https://www.instagram.com/queitnay?igsh=MXZxeXJlMW1zMThrOQ==" target="_blank">MUHAMMAD HUSNI MUBAROK M</a></p>
                </div>
            </div>
            
            <div class="footer-col map-col">
                <div class="map-label">Maps</div>
                <div class="map-wrapper">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.555897667335!2d107.78035943978011!3d-6.577592664327521!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e693b4130dcd33f%3A0xb8f2d91a2faaa0fe!2sUniversitas%20Subang!5e0!3m2!1sid!2sid!4v1780970528702!5m2!1sid!2sid" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="footer-credits">
                    <p>Created by Mahasiswa Semester 4 FASILKOM UNSUB</p>
                    <p>@proyekperangkatlunak</p>
                    <p>Copyright @ 2026 Kelas 4 Regular B</p>
                </div>
            </div>
        </div>
    </footer>
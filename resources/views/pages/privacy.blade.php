<x-layouts.frontend>
    @php
        $settings = \App\Models\Setting::first();
        $sections = $page->getSection('sections', []);
        $isEnglish = app()->getLocale() === 'en';
    @endphp

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-16 lg:pt-40 lg:pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <span class="inline-block text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-4">
                        {{ $isEnglish ? 'Legal Notice' : 'Rechtliches' }}
                    </span>
                    <h1>{{ $page->title }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- 1. Datenschutz auf einen Blick --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? '1. Privacy at a Glance' : '1. Datenschutz auf einen Blick' }}</h2>

                <div class="space-y-8">
                    {{-- Allgemeine Hinweise --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'General Information' : 'Allgemeine Hinweise' }}</h3>
                        <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                            @if($isEnglish)
                                The following information provides a simple overview of what happens to your personal data when you visit this website. Personal data is any data that can personally identify you. For detailed information on data protection, please refer to our privacy policy listed below.
                            @else
                                Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen Daten passiert, wenn Sie diese Website besuchen. Personenbezogene Daten sind alle Daten, mit denen Sie persönlich identifiziert werden können. Ausführliche Informationen zum Thema Datenschutz entnehmen Sie unserer unter diesem Text aufgeführten Datenschutzerklärung.
                            @endif
                        </p>
                    </div>

                    {{-- Datenerfassung --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Data Collection on This Website' : 'Datenerfassung auf dieser Website' }}</h3>
                        <div class="space-y-4 text-[1.0625rem] text-muted-foreground leading-relaxed">
                            <div>
                                <p class="font-medium text-foreground mb-2">{{ $isEnglish ? 'Who is responsible for data collection on this website?' : 'Wer ist verantwortlich für die Datenerfassung auf dieser Website?' }}</p>
                                <p>
                                    @if($isEnglish)
                                        Data processing on this website is carried out by the website operator. You can find their contact details in the "Controller Information" section of this privacy policy.
                                    @else
                                        Die Datenverarbeitung auf dieser Website erfolgt durch den Websitebetreiber. Dessen Kontaktdaten können Sie dem Abschnitt „Hinweis zur verantwortlichen Stelle" in dieser Datenschutzerklärung entnehmen.
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="font-medium text-foreground mb-2">{{ $isEnglish ? 'How do we collect your data?' : 'Wie erfassen wir Ihre Daten?' }}</p>
                                <p>
                                    @if($isEnglish)
                                        Your data is collected when you provide it to us, for example, by entering data in a contact form. Other data is automatically collected by our IT systems when you visit the website. This is primarily technical data (e.g., internet browser, operating system, or time of page access). This data is collected automatically as soon as you enter this website.
                                    @else
                                        Ihre Daten werden zum einen dadurch erhoben, dass Sie uns diese mitteilen. Hierbei kann es sich z.B. um Daten handeln, die Sie in ein Kontaktformular eingeben. Andere Daten werden automatisch oder nach Ihrer Einwilligung beim Besuch der Website durch unsere IT-Systeme erfasst. Das sind vor allem technische Daten (z.B. Internetbrowser, Betriebssystem oder Uhrzeit des Seitenaufrufs). Die Erfassung dieser Daten erfolgt automatisch, sobald Sie diese Website betreten.
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="font-medium text-foreground mb-2">{{ $isEnglish ? 'What do we use your data for?' : 'Wofür nutzen wir Ihre Daten?' }}</p>
                                <p>
                                    @if($isEnglish)
                                        Part of the data is collected to ensure the error-free provision of the website. Other data may be used to analyze your user behavior.
                                    @else
                                        Ein Teil der Daten wird erhoben, um eine fehlerfreie Bereitstellung der Website zu gewährleisten. Andere Daten können zur Analyse Ihres Nutzerverhaltens verwendet werden.
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="font-medium text-foreground mb-2">{{ $isEnglish ? 'What rights do you have regarding your data?' : 'Welche Rechte haben Sie bezüglich Ihrer Daten?' }}</p>
                                <p>
                                    @if($isEnglish)
                                        You have the right to receive information about the origin, recipient, and purpose of your stored personal data free of charge at any time. You also have the right to request the correction or deletion of this data. If you have given consent to data processing, you can revoke this consent at any time for the future. You also have the right to request the restriction of the processing of your personal data under certain circumstances. Furthermore, you have the right to lodge a complaint with the competent supervisory authority.
                                    @else
                                        Sie haben jederzeit das Recht, unentgeltlich Auskunft über Herkunft, Empfänger und Zweck Ihrer gespeicherten personenbezogenen Daten zu erhalten. Sie haben außerdem ein Recht, die Berichtigung oder Löschung dieser Daten zu verlangen. Wenn Sie eine Einwilligung zur Datenverarbeitung erteilt haben, können Sie diese Einwilligung jederzeit für die Zukunft widerrufen. Außerdem haben Sie das Recht, unter bestimmten Umständen die Einschränkung der Verarbeitung Ihrer personenbezogenen Daten zu verlangen. Des Weiteren steht Ihnen ein Beschwerderecht bei der zuständigen Aufsichtsbehörde zu.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Hosting --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? '2. Hosting' : '2. Hosting' }}</h2>

                <div class="space-y-6 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    <p>
                        @if($isEnglish)
                            We host the content of our website with the following provider:
                        @else
                            Wir hosten die Inhalte unserer Website bei folgendem Anbieter:
                        @endif
                    </p>

                    <div>
                        <h3 class="text-[1.0625rem] font-medium text-foreground mb-4">{{ $isEnglish ? 'External Hosting' : 'Externes Hosting' }}</h3>
                        <p class="mb-4">
                            @if($isEnglish)
                                This website is hosted externally. The personal data collected on this website is stored on the servers of the host(s). This may include IP addresses, contact requests, meta and communication data, contract data, contact details, names, website accesses, and other data generated via a website.
                            @else
                                Diese Website wird extern gehostet. Die personenbezogenen Daten, die auf dieser Website erfasst werden, werden auf den Servern des Hosters gespeichert. Hierbei kann es sich v.a. um IP-Adressen, Kontaktanfragen, Meta- und Kommunikationsdaten, Vertragsdaten, Kontaktdaten, Namen, Websitezugriffe und sonstige Daten, die über eine Website generiert werden, handeln.
                            @endif
                        </p>
                        <p>
                            @if($isEnglish)
                                External hosting is carried out for the purpose of fulfilling contracts with our potential and existing customers (Art. 6 para. 1 lit. b GDPR) and in the interest of a secure, fast, and efficient provision of our online offering by a professional provider (Art. 6 para. 1 lit. f GDPR).
                            @else
                                Das externe Hosting erfolgt zum Zwecke der Vertragserfüllung gegenüber unseren potenziellen und bestehenden Kunden (Art. 6 Abs. 1 lit. b DSGVO) und im Interesse einer sicheren, schnellen und effizienten Bereitstellung unseres Online-Angebots durch einen professionellen Anbieter (Art. 6 Abs. 1 lit. f DSGVO).
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Allgemeine Hinweise und Pflichtinformationen --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? '3. General Information and Mandatory Disclosures' : '3. Allgemeine Hinweise und Pflichtinformationen' }}</h2>

                <div class="space-y-8">
                    {{-- Datenschutz --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Data Protection' : 'Datenschutz' }}</h3>
                        <div class="space-y-4 text-[1.0625rem] text-muted-foreground leading-relaxed">
                            <p>
                                @if($isEnglish)
                                    The operators of these pages take the protection of your personal data very seriously. We treat your personal data confidentially and in accordance with the statutory data protection regulations and this privacy policy.
                                @else
                                    Die Betreiber dieser Seiten nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Wir behandeln Ihre personenbezogenen Daten vertraulich und entsprechend den gesetzlichen Datenschutzvorschriften sowie dieser Datenschutzerklärung.
                                @endif
                            </p>
                            <p>
                                @if($isEnglish)
                                    When you use this website, various personal data is collected. Personal data is data that can personally identify you. This privacy policy explains what data we collect and what we use it for. It also explains how and for what purpose this is done.
                                @else
                                    Wenn Sie diese Website benutzen, werden verschiedene personenbezogene Daten erhoben. Personenbezogene Daten sind Daten, mit denen Sie persönlich identifiziert werden können. Die vorliegende Datenschutzerklärung erläutert, welche Daten wir erheben und wofür wir sie nutzen. Sie erläutert auch, wie und zu welchem Zweck das geschieht.
                                @endif
                            </p>
                            <p>
                                @if($isEnglish)
                                    We would like to point out that data transmission over the Internet (e.g., communication by email) may have security vulnerabilities. Complete protection of data against access by third parties is not possible.
                                @else
                                    Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Verantwortliche Stelle --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Controller Information' : 'Hinweis zur verantwortlichen Stelle' }}</h3>
                        <div class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                            <p class="mb-4">
                                @if($isEnglish)
                                    The controller responsible for data processing on this website is:
                                @else
                                    Die verantwortliche Stelle für die Datenverarbeitung auf dieser Website ist:
                                @endif
                            </p>
                            <div class="space-y-1 mb-4">
                                <p>{{ $settings->company_name }}</p>
                                <p>{{ $settings->owner_name }}</p>
                                <p>{{ $settings->street }}</p>
                                <p>{{ $settings->postal_code }} {{ $settings->city }}@if($isEnglish), Germany @endif</p>
                            </div>
                            <div class="space-y-1 mb-4">
                                @if($settings->phone)<p>{{ $isEnglish ? 'Phone' : 'Telefon' }}: {{ $settings->phone }}</p>@endif
                                <p>{{ $isEnglish ? 'Email' : 'E-Mail' }}: {{ $settings->email }}</p>
                            </div>
                            <p>
                                @if($isEnglish)
                                    The controller is the natural or legal person who alone or jointly with others determines the purposes and means of the processing of personal data (e.g., names, email addresses, etc.).
                                @else
                                    Verantwortliche Stelle ist die natürliche oder juristische Person, die allein oder gemeinsam mit anderen über die Zwecke und Mittel der Verarbeitung von personenbezogenen Daten (z.B. Namen, E-Mail-Adressen o. Ä.) entscheidet.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Speicherdauer --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Storage Duration' : 'Speicherdauer' }}</h3>
                        <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                            @if($isEnglish)
                                Unless a more specific storage period has been specified within this privacy policy, your personal data will remain with us until the purpose for data processing no longer applies. If you assert a legitimate request for deletion or revoke consent to data processing, your data will be deleted unless we have other legally permissible reasons for storing your personal data (e.g., tax or commercial law retention periods); in the latter case, the deletion will take place after these reasons no longer apply.
                            @else
                                Soweit innerhalb dieser Datenschutzerklärung keine speziellere Speicherdauer genannt wurde, verbleiben Ihre personenbezogenen Daten bei uns, bis der Zweck für die Datenverarbeitung entfällt. Wenn Sie ein berechtigtes Löschersuchen geltend machen oder eine Einwilligung zur Datenverarbeitung widerrufen, werden Ihre Daten gelöscht, sofern wir keine anderen rechtlich zulässigen Gründe für die Speicherung Ihrer personenbezogenen Daten haben (z.B. steuer- oder handelsrechtliche Aufbewahrungsfristen); im letztgenannten Fall erfolgt die Löschung nach Fortfall dieser Gründe.
                            @endif
                        </p>
                    </div>

                    {{-- Widerruf --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Revocation of Your Consent to Data Processing' : 'Widerruf Ihrer Einwilligung zur Datenverarbeitung' }}</h3>
                        <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                            @if($isEnglish)
                                Many data processing operations are only possible with your express consent. You can revoke consent that you have already given at any time. The legality of the data processing carried out until the revocation remains unaffected by the revocation.
                            @else
                                Viele Datenverarbeitungsvorgänge sind nur mit Ihrer ausdrücklichen Einwilligung möglich. Sie können eine bereits erteilte Einwilligung jederzeit widerrufen. Die Rechtmäßigkeit der bis zum Widerruf erfolgten Datenverarbeitung bleibt vom Widerruf unberührt.
                            @endif
                        </p>
                    </div>

                    {{-- Beschwerderecht --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Right to Lodge a Complaint' : 'Beschwerderecht bei der zuständigen Aufsichtsbehörde' }}</h3>
                        <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                            @if($isEnglish)
                                In the event of violations of the GDPR, data subjects have the right to lodge a complaint with a supervisory authority, in particular in the Member State of their habitual residence, place of work, or the place of the alleged infringement. The right to lodge a complaint is without prejudice to any other administrative or judicial remedy.
                            @else
                                Im Falle von Verstößen gegen die DSGVO steht den Betroffenen ein Beschwerderecht bei einer Aufsichtsbehörde, insbesondere in dem Mitgliedstaat ihres gewöhnlichen Aufenthalts, ihres Arbeitsplatzes oder des Orts des mutmaßlichen Verstoßes zu. Das Beschwerderecht besteht unbeschadet anderweitiger verwaltungsrechtlicher oder gerichtlicher Rechtsbehelfe.
                            @endif
                        </p>
                    </div>

                    {{-- Datenportabilitaet --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Right to Data Portability' : 'Recht auf Datenübertragbarkeit' }}</h3>
                        <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                            @if($isEnglish)
                                You have the right to have data that we process automatically on the basis of your consent or in fulfillment of a contract handed over to you or to a third party in a common, machine-readable format. If you request the direct transfer of the data to another controller, this will only be done to the extent technically feasible.
                            @else
                                Sie haben das Recht, Daten, die wir auf Grundlage Ihrer Einwilligung oder in Erfüllung eines Vertrags automatisiert verarbeiten, an sich oder an einen Dritten in einem gängigen, maschinenlesbaren Format aushändigen zu lassen. Sofern Sie die direkte Übertragung der Daten an einen anderen Verantwortlichen verlangen, erfolgt dies nur, soweit es technisch machbar ist.
                            @endif
                        </p>
                    </div>

                    {{-- SSL/TLS --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'SSL/TLS Encryption' : 'SSL- bzw. TLS-Verschlüsselung' }}</h3>
                        <div class="space-y-4 text-[1.0625rem] text-muted-foreground leading-relaxed">
                            <p>
                                @if($isEnglish)
                                    This site uses SSL or TLS encryption for security reasons and to protect the transmission of confidential content, such as orders or inquiries that you send to us as the site operator. You can recognize an encrypted connection by the fact that the address line of the browser changes from "http://" to "https://" and by the lock symbol in your browser line.
                                @else
                                    Diese Seite nutzt aus Sicherheitsgründen und zum Schutz der Übertragung vertraulicher Inhalte, wie zum Beispiel Bestellungen oder Anfragen, die Sie an uns als Seitenbetreiber senden, eine SSL- bzw. TLS-Verschlüsselung. Eine verschlüsselte Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von „http://" auf „https://" wechselt und an dem Schloss-Symbol in Ihrer Browserzeile.
                                @endif
                            </p>
                            <p>
                                @if($isEnglish)
                                    If SSL or TLS encryption is activated, the data you transmit to us cannot be read by third parties.
                                @else
                                    Wenn die SSL- bzw. TLS-Verschlüsselung aktiviert ist, können die Daten, die Sie an uns übermitteln, nicht von Dritten mitgelesen werden.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Auskunft, Loeschung, Berichtigung --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Information, Deletion, and Correction' : 'Auskunft, Löschung und Berichtigung' }}</h3>
                        <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                            @if($isEnglish)
                                Within the framework of the applicable legal provisions, you have the right to free information about your stored personal data, its origin and recipients, and the purpose of data processing and, if applicable, a right to correction or deletion of this data at any time. For this purpose, as well as for further questions on the subject of personal data, you can contact us at any time.
                            @else
                                Sie haben im Rahmen der geltenden gesetzlichen Bestimmungen jederzeit das Recht auf unentgeltliche Auskunft über Ihre gespeicherten personenbezogenen Daten, deren Herkunft und Empfänger und den Zweck der Datenverarbeitung und ggf. ein Recht auf Berichtigung oder Löschung dieser Daten. Hierzu sowie zu weiteren Fragen zum Thema personenbezogene Daten können Sie sich jederzeit an uns wenden.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Datenerfassung auf dieser Website --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? '4. Data Collection on This Website' : '4. Datenerfassung auf dieser Website' }}</h2>

                <div class="space-y-8">
                    {{-- Server-Log-Dateien --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Server Log Files' : 'Server-Log-Dateien' }}</h3>
                        <div class="space-y-4 text-[1.0625rem] text-muted-foreground leading-relaxed">
                            <p>
                                @if($isEnglish)
                                    The provider of the pages automatically collects and stores information in so-called server log files, which your browser automatically transmits to us. These are:
                                @else
                                    Der Provider der Seiten erhebt und speichert automatisch Informationen in so genannten Server-Log-Dateien, die Ihr Browser automatisch an uns übermittelt. Dies sind:
                                @endif
                            </p>
                            <ul class="list-disc list-inside space-y-1 ml-4">
                                <li>{{ $isEnglish ? 'Browser type and version' : 'Browsertyp und Browserversion' }}</li>
                                <li>{{ $isEnglish ? 'Operating system used' : 'Verwendetes Betriebssystem' }}</li>
                                <li>{{ $isEnglish ? 'Referrer URL' : 'Referrer URL' }}</li>
                                <li>{{ $isEnglish ? 'Hostname of the accessing computer' : 'Hostname des zugreifenden Rechners' }}</li>
                                <li>{{ $isEnglish ? 'Time of server request' : 'Uhrzeit der Serveranfrage' }}</li>
                                <li>{{ $isEnglish ? 'IP address' : 'IP-Adresse' }}</li>
                            </ul>
                            <p>
                                @if($isEnglish)
                                    This data is not merged with other data sources. The collection of this data is based on Art. 6 para. 1 lit. f GDPR. The website operator has a legitimate interest in the technically error-free presentation and optimization of their website – for this purpose, the server log files must be recorded.
                                @else
                                    Eine Zusammenführung dieser Daten mit anderen Datenquellen wird nicht vorgenommen. Die Erfassung dieser Daten erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Der Websitebetreiber hat ein berechtigtes Interesse an der technisch fehlerfreien Darstellung und der Optimierung seiner Website – hierzu müssen die Server-Log-Dateien erfasst werden.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Kontaktformular --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Contact Form' : 'Kontaktformular' }}</h3>
                        <div class="space-y-4 text-[1.0625rem] text-muted-foreground leading-relaxed">
                            <p>
                                @if($isEnglish)
                                    If you send us inquiries via the contact form, your details from the inquiry form, including the contact details you provide there, will be stored by us for the purpose of processing the inquiry and in case of follow-up questions. We will not share this data without your consent.
                                @else
                                    Wenn Sie uns per Kontaktformular Anfragen zukommen lassen, werden Ihre Angaben aus dem Anfrageformular inklusive der von Ihnen dort angegebenen Kontaktdaten zwecks Bearbeitung der Anfrage und für den Fall von Anschlussfragen bei uns gespeichert. Diese Daten geben wir nicht ohne Ihre Einwilligung weiter.
                                @endif
                            </p>
                            <p>
                                @if($isEnglish)
                                    The processing of this data is based on Art. 6 para. 1 lit. b GDPR, if your inquiry is related to the performance of a contract or is necessary for the implementation of pre-contractual measures. In all other cases, the processing is based on our legitimate interest in the effective processing of inquiries addressed to us (Art. 6 para. 1 lit. f GDPR) or on your consent (Art. 6 para. 1 lit. a GDPR) if this was requested.
                                @else
                                    Die Verarbeitung dieser Daten erfolgt auf Grundlage von Art. 6 Abs. 1 lit. b DSGVO, sofern Ihre Anfrage mit der Erfüllung eines Vertrags zusammenhängt oder zur Durchführung vorvertraglicher Maßnahmen erforderlich ist. In allen übrigen Fällen beruht die Verarbeitung auf unserem berechtigten Interesse an der effektiven Bearbeitung der an uns gerichteten Anfragen (Art. 6 Abs. 1 lit. f DSGVO) oder auf Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO) sofern diese abgefragt wurde.
                                @endif
                            </p>
                            <p>
                                @if($isEnglish)
                                    The data you enter in the contact form will remain with us until you request deletion, revoke your consent to storage, or the purpose for data storage no longer applies (e.g., after your request has been processed). Mandatory legal provisions – in particular retention periods – remain unaffected.
                                @else
                                    Die von Ihnen im Kontaktformular eingegebenen Daten verbleiben bei uns, bis Sie uns zur Löschung auffordern, Ihre Einwilligung zur Speicherung widerrufen oder der Zweck für die Datenspeicherung entfällt (z.B. nach abgeschlossener Bearbeitung Ihrer Anfrage). Zwingende gesetzliche Bestimmungen – insbesondere Aufbewahrungsfristen – bleiben unberührt.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Anfrage per E-Mail --}}
                    <div>
                        <h3 class="text-[1.0625rem] font-medium mb-4">{{ $isEnglish ? 'Inquiry by Email' : 'Anfrage per E-Mail' }}</h3>
                        <div class="space-y-4 text-[1.0625rem] text-muted-foreground leading-relaxed">
                            <p>
                                @if($isEnglish)
                                    If you contact us by email, your inquiry including all resulting personal data (name, inquiry) will be stored and processed by us for the purpose of processing your request. We will not share this data without your consent.
                                @else
                                    Wenn Sie uns per E-Mail kontaktieren, wird Ihre Anfrage inklusive aller daraus hervorgehenden personenbezogenen Daten (Name, Anfrage) zum Zwecke der Bearbeitung Ihres Anliegens bei uns gespeichert und verarbeitet. Diese Daten geben wir nicht ohne Ihre Einwilligung weiter.
                                @endif
                            </p>
                            <p>
                                @if($isEnglish)
                                    The data sent to us via contact requests remains with us until you request deletion, revoke your consent to storage, or the purpose for data storage no longer applies (e.g., after your request has been processed). Mandatory legal provisions – in particular statutory retention periods – remain unaffected.
                                @else
                                    Die von Ihnen an uns per Kontaktanfragen übersandten Daten verbleiben bei uns, bis Sie uns zur Löschung auffordern, Ihre Einwilligung zur Speicherung widerrufen oder der Zweck für die Datenspeicherung entfällt (z.B. nach abgeschlossener Bearbeitung Ihres Anliegens). Zwingende gesetzliche Bestimmungen – insbesondere gesetzliche Aufbewahrungsfristen – bleiben unberührt.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Zusaetzliche Abschnitte aus dem CMS --}}
    @foreach($sections as $section)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $section['heading'] }}</h2>
                <div class="text-[1.0625rem] text-muted-foreground leading-relaxed [&_a]:text-accent [&_a:hover]:underline [&_ul]:list-disc [&_ul]:list-inside [&_ul]:space-y-1 [&_ul]:ml-4">
                    {!! $section['content'] !!}
                </div>
            </div>
        </div>
    </section>
    @endforeach
</x-layouts.frontend>

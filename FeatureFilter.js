class FeatureFilter
{
    constructor(accountPrefix) {
        this.isOn = 'agcrm-wp'; // which platform is this running on?
        this.accountPrefix = accountPrefix; // account prefix when relative URL won't work
        this.filterComplete; // filtering has been completed
        this.partsActive = {}; // all parts which are visible, indexed by part id
        this.partsHidden = {}; // all parts which are hidden, indexed by part id
        this._glassType; // INT glass type id
        this._glassOpt; // INT glass opt id
        this.searchid; // INT id of original search

        this.partsByFeature = {}; // arrays of part ids for features to filter
        this._features; // features to filter
        this.canFilterInternal; // based on internal data, can this feature set be filtered?
        this.activeFeature; // feature currently displayed
        this.selectedFeaturesTitles = {}; // titles of selected features

        this.initialDisplay; // display value of part UI container before hiding
        this.trackingId; // search_questions_tracking id
    }

    /**
     * Receive and load initial search data
     * @return Promise which resolves when fully loaded
     */
    load(parts, glassType, glassOpt, searchid) {
        var self = this;
        return new Promise(function(resolve, reject) {
            self._glassType = glassType; // INT glass type id
            self._glassOpt = glassOpt; // INT glass opt id
            self.searchid = searchid;
            for(let p in parts) {
                self.loadPart(parts[p]);
            }
            resolve();
        });
    }

    /**
     * Load each individual part
     */
    loadPart(part) {
        this.partsActive[part.id] = part;
        if(part.features) {
            if(part.count > 5 && this.canFilterInternal !== false) {
                this.canFilterInternal = true;
            }
            this.loadPartFeatures(part);
        } else {
            this.canFilterInternal = false;
        }
    }

    /**
     * Load features for this part
     */
    loadPartFeatures(part) {
        for(let f in part.features) { // iterate features
            if(part.features[f] === '1') { // part has this feature
                if(this.partsByFeature[f]) {
                    this.partsByFeature[f].push(part.id);
                } else {
                    this.partsByFeature[f] = [part.id];
                }
            }
        }
    }

    /**
     * Filter by set of features
     * @param array of feature slugs
     * @return array of matching parts
     */
    filterBy(features) {
        if(typeof this.filterComplete === 'boolean') {
            this.reset(); // in case it was opened before, then closed
        }
        this.filterComplete = false;
        for(let i in features) {
            if(this.partsByFeature.hasOwnProperty(features[i])) {
                for(let id in this.partsActive) { // iterate each active part
                    if(!this.partsByFeature[features[i]].includes(id)) { // this part isn't listed for this feature, so remove
                        this.setPartInactive(id);
                    }
                }
            }
            this.clearIrrelevantFeatures();
        }
        this.filterComplete = true;
        return this.partsActive;
    }

	getQuestions(params)
	{
		var url = WPURLS.admin_url + '/admin-ajax.php?action=quote_get_questions';
		return jQuery.ajax(url, {
			type: "POST",
			dataType: "json",
			data: {
				'params': params
			}
		})
	}

    /**
     * Get questions by feature and display filter UI
     * TODO UPDATE FOR DIFFERENT PLATFORM
     */
    showFilterModal() {
        if(typeof this.filterComplete === 'boolean') {
            this.reset(); // in case it was opened before, then closed
        }
        this.filterComplete = false;
        let fstring = this.features.join('-');
        let params = '?features='+fstring+'&type='+this.glassType+(this.glassOpt ? '&opt='+this.glassOpt : '');
		let parentInstance = this;
		this.getQuestions(params).done(function(data){
			jQuery("#featureFilterModal").addClass("modal-active");
			var html = "";

			for(let id in data){
				let item = data[id];
				html += '<div id="question-' + item.slug + '" class="bg-blue-pale filter-question-box" style="display:none">';
				html += 	'<p class="filter-question-title">' + item.title + '</p>';
				html +=		'<p>' + item.text + '</p>';

				if ( item.photos.length > 0 ){
					html += '<div class="filter-question-photos cards-container">';
					for(let photo in item.photos){
						html += '<div class="filter-question-loader card bg-blue-palest width-300 mobile-full-width" data-caption="' + item.photos[photo].caption + '" data-url="' + item.photos[photo].url + '"></div>';
					}
					html += '</div>';

					html += '<div class="filter-question-video" data-feature="' + item.slug + '" style="display: none"></div>';
				}


				html += '<div class="filter-function-buttons">';
                html +=		'<button type="button" class="btn-dark btn-small question-yes">';
				html +=			'<i class="far fa-check-circle"></i>&nbsp;&nbsp;Yes';
                html +=		'</button>';
                html +=		'<button type="button" class="btn-dark btn-small question-no">';
                html +=			'<i class="far fa-times-circle"></i>&nbsp;&nbsp;No';
                html +=		'</button>';
                html +=		'<button type="button" class="btn-dark btn-small question-notsure">';
                html +=			'<i class="far fa-question-circle"></i>&nbsp;&nbsp;Not Sure';
                html +=		'</button>';
				html +=	'</div>';


				html += '</div>';

			}

			jQuery("#vd-filter-questions").html(html);
			parentInstance.initListeners();
			parentInstance.askNextQuestion();
			parentInstance.loadImagesAndVideo();
		}).fail(function(){

		});

		/*
        loadModalDash('Filter Parts by Feature', url, {width: '700'}).then(() => {
            this.initListeners();
            this.askNextQuestion();

            // reset filtering if filter UI is closed
            $('body').on('modalClosed', '.modal', () => {
                if(!this.filterComplete) {
                    this.reset();
                }
            });
            // open viewer to see photos more closely
            $('.filter-question-loader').on('click', 'img', function() {
                new Viewer($(this).clone());
            });

            this.loadImagesAndVideo();
        });
		*/
    }

    /**
     * Update UI when filtering is complete
     * TODO UPDATE FOR DIFFERENT PLATFORM
     */
    displayFilterResults() {
        this.filterComplete = true;
        jQuery("#featureFilterModal").removeClass("modal-active");
		jQuery("#btnQuoteSubmit").removeClass("hidden");

		jQuery("#messagePopup").removeClass("hidden");
		jQuery("#messagePopup .modal_inner .modal_body").html("<p>Thank you for your answers.</p><p>Click the submit button to send in your request</p>");

        // couldn't filter to one part
        if(this.partsActiveCount > 1) {
            //jQuery('#help-request-box').show(); // allow help request
            //var msgs = new MessagesNew('We have narrowed your options as much as we could based on the features you selected. If you aren\'t sure which to use, click "Tell me the exact part" below to ask for help.', 'info');

        }
        // filtered to one part
        else {
            this.saveToSearchHistory();
            //var msgs = new MessagesNew('Based on the features you selected, this part should be the one you need for your vehicle.', 'info');
        }

        // display selected features in confirmation message
		/*
        if(Object.keys(this.selectedFeaturesTitles).length) {
            msgs.append('<b>Selected features: '+this.selectedFeaturesTitlesStr+'</b>');
        }
        msgs.display();
		*/

		jQuery(".vin_search_result .part_item").addClass("hidden");
		var partNums = "";
		for(let partId in this.partsActive){
			jQuery(".vin_search_result .part_item[data-part-id='" + partId + "']").removeClass("hidden");
			if ( partNums == "" ){
				partNums = this.partsActive[partId].part_number;
			}
			else{
				partNums += ", " + this.partsActive[partId].part_number;
			}
		}

		jQuery("#formUploadPhoto input[name='possible_parts']").val(partNums);

    }

    /**
     * Move to next question, updating features queue and UI as needed
     */
    askNextQuestion() {
        this.clearIrrelevantFeatures(); // remove features which appear for all parts
        this.activeFeature = this.features.pop();
        document.getElementById('question-'+this.activeFeature).style.display = 'block';
    }

    /**
     * Update features and UI based on user input
     */
    runFilter(response) {
        // response is yes
        if(response === 'yes') {
            this.addSelectedFeature();
            for(let id in this.partsActive) { // iterate each active part
                if(!this.partsByFeature[this.activeFeature].includes(id)) { // this part isn't listed for this feature, so hide
                    this.setPartInactive(id);
                }
            }
        }
        // response is no
        else if(response === 'no') {
            for(let id in this.partsActive) { // iterate each active part
                if(this.partsByFeature[this.activeFeature].includes(id)) { // this part is listed for this feature, so hide
                    this.setPartInactive(id);
                }
            }
        }

        // ask next question, if applicable
        if(this.partsActiveCount > 1 && this.features.length) {
            document.getElementById('question-'+this.activeFeature).style.display = 'none';
            this.askNextQuestion();
        }

        // or display filtered list because we have no more questions
        else {
            this.displayFilterResults(); // update UI with results
            this.saveFilterResults(); // save results for tracking
        }
    }

    /**
     * If feature is selected by user, get title and append it to this.selectedFeaturesTitles
     */
    addSelectedFeature() {
        let featureTitle = document.getElementById('question-'+this.activeFeature).getElementsByClassName('filter-question-title')[0].innerHTML.trim();
        this.selectedFeaturesTitles[this.activeFeature] = featureTitle;
    }

    /**
     * Run when a part has been filtered out by user selection
     */
    setPartInactive(partid) {
        let elem = this.partUI(partid);
        if(elem) {
            this.initialDisplay = elem.style.display;
            elem.style.display = 'none'; // hide in UI
        }

        // update this object
        this.partsHidden[partid] = this.partsActive[partid];
        delete this.partsActive[partid];
    }

    /**
     * Restore/reset all parts to pre-filtering
     */
    restoreInactivePart(partid) {
        // restore in UI
        let elem = this.partUI(partid);
        if(elem) {
            elem.style.display = this.initialDisplay;
        }

        // update this object
        this.partsActive[partid] = this.partsHidden[partid];
        delete this.partsHidden[partid];
    }

    /**
     * Set initial event listeners
     */
    initListeners() {
        // reset and restart filtering
        document.getElementById('start-filter-again').addEventListener('click', () => {
            this.reset();
            this.askNextQuestion();
        });

        // hooks for yes/no/not sure buttons
        let qdiv = document.getElementById('vd-filter-questions'),
            questions = qdiv.getElementsByClassName('question-yes');
        for(let i = 0; i < questions.length; i++) {
            questions[i].addEventListener('click', () => {
                this.runFilter('yes');
            });
        }
        questions = qdiv.getElementsByClassName('question-no');
        for(let i = 0; i < questions.length; i++) {
            questions[i].addEventListener('click', () => {
                this.runFilter('no');
            });
        }
        questions = qdiv.getElementsByClassName('question-notsure');
        for(let i = 0; i < questions.length; i++) {
            questions[i].addEventListener('click', () => {
                this.runFilter('notsure');
            });
        }
    }

    /**
     * Load images and video for each question
     */
    loadImagesAndVideo() {
        // load video and images of active question
        let activeQuestionDiv = document.getElementById('question-'+this.activeFeature);
        let loader = activeQuestionDiv.getElementsByClassName('filter-question-loader');
        for(let i = 0; i < loader.length; i++) {
            this.loadImages(loader[i]);
        }
        loader = activeQuestionDiv.getElementsByClassName('filter-question-video');
        for(let i = 0; i < loader.length; i++) {
            this.loadVideo(loader[i]);
        }

        // load video and images of all other questions
        let questionDivs = document.getElementsByClassName('filter-question-box'); // get all question divs
        for(let i = 0; i < questionDivs.length; i++) {
            if(questionDivs[i].id === 'question-'+this.activeFeature) continue;
            let loader = questionDivs[i].getElementsByClassName('filter-question-loader');
            for(let i = 0; i < loader.length; i++) {
                if(loader[i].id === 'question-'+this.activeFeature) continue;
                this.loadImages(loader[i]);
            }
            loader = questionDivs[i].getElementsByClassName('filter-question-video');
            for(let i = 0; i < loader.length; i++) {
                if(loader[i].id === 'question-'+this.activeFeature) continue;
                this.loadVideo(loader[i]);
            }
        }
    }

    /**
     * Load images from URLs after filter UI loads
     * TODO UPDATE FOR DIFFERENT PLATFORM
     */
    loadImages(div) {
        let $div = jQuery(div),
            caption = $div.data('caption');
        if(caption) {
            $div.append(jQuery('<div></div>').html($div.data('caption')).addClass('card-header'));
        }
        $div.append(
            jQuery('<img />').attr({
                src: $div.data('url')
            })
        );
    }

    /**
     * Display part-specific video to illustrate feature, if available
     */
    loadVideo(div) {
        var feature = div.dataset.feature;
        if(!this.partsByFeature[feature]) return; // won't exist if cleared by clearIrrelevantFeatures
        // is there a part with this feature with video?
        for(let partid in this.partsActive) {
            if(this.partsByFeature[feature].includes(partid)) { // this part has the active feature
                if(this.partsActive[partid].videos.length) { // this part has a video
                    let featureTitle = document.getElementById('question-'+feature).getElementsByClassName('filter-question-title')[0].innerHTML,
                        youtubeURL = 'http://youtu.be/'+this.partsActive[partid].videos[0].playid,
                        newLink = document.createElement('a');
                    newLink.href = youtubeURL;
                    newLink.target = "_blank";
                    newLink.appendChild(
                        document.createTextNode('Still not sure? Click here to open a YouTube video showing exactly what '+featureTitle+' looks like for your vehicle. (Video may also show other features not on your model.)')
                    );
                    div.appendChild(newLink);
                    div.style.display = 'block';
                    return;
                }
            }
        }
    }

    /**
     * If a feature appears in ALL parts, it doesn't help filtering, so clear it
     * Run this method after each question answer
     */
    clearIrrelevantFeatures() {
        // iterate lists of parts associated with each feature
        for(let f in this.partsByFeature) {
            // iterate each active part: do all active parts have this feature?
            var deletePart = true;
            for(let id in this.partsActive) {
                if(!this.partsByFeature[f].includes(id)) { // this part doesn't have this feature, so filter
                    deletePart = false;
                    break;
                }
            }
            if(deletePart) {
                delete this.partsByFeature[f];
                this._features = Object.keys(this.partsByFeature);
            }
        }
    }

    /**
     * Reset UI and features back to initial state, clearing any previous filtering
     */
    reset() {
        // hide all questions
        let qdiv = document.getElementById('vd-filter-questions');
        if(qdiv) {
            let questions = qdiv.getElementsByClassName('filter-question-box');
            for(let i = 0; i < questions.length; i++) {
                questions[i].style.display = 'none';
            }
        }
        // restore visiblity of parts and set all active
        for(let partid in this.partsHidden) {
            this.restoreInactivePart(partid);
        }

        // reset object
        this.partsByFeature = {};
        for(let i in this.partsActive) {
            this.loadPartFeatures(this.partsActive[i]);
        }
        this._features = Object.keys(this.partsByFeature);
        this.selectedFeaturesTitles = {};
        this.activeFeature = null;
    }

    /**
     * Save initial search results for filter tracking
     */
    saveSearchInitial(vehicledata) {
        if(this.isOn === 'vd' || this.isOn === 'agcrm-wp') return; // don't save from some platforms
        var self = this,
            url = (this.accountPrefix ? 'https://'+this.accountPrefix+'.autoglasscrm.com' : '')+'/vindecoder/savesearchinitial',
            parts = [];
        // iterate parts
        for(let p in this.partsActive) {
            let features = '';
            // iterate features per part
            for(let f in this.partsActive[p].features) {
                if(this.partsActive[p].features[f] === '1') {
                    features += f+', ';
                }
            }
            // no features
            if(!features.length) {
                if(this.partsActive[p].features) { // ...because has none
                    features = 'none';
                } else { // ...because we haven't listed them yet
                    features = '';
                }
            }
            parts.push({
                id: this.partsActive[p].id,
                part_number: this.partsActive[p].part_number,
                usecount: this.partsActive[p].count,
                features: features.replace(/,\s$/, '')
            });
        }
        let data = {
            sid: this.searchid,
            gti: this.glassType,
            goi: this.glassOpt,
            vin: vehicledata.vin,
            year: vehicledata.year,
            make: vehicledata.make,
            model: vehicledata.model,
            body: vehicledata.body,
            canfilter: (this.canFilter ? 1 : 0),
            parts: parts,
            from: this.isOn
        };
        this.ajax('POST', url, data)
        .then(function(response) {
            self.trackingId = response;
        });
    }

    /**
     * Save filter results for tracking
     */
    saveFilterResults() {
        if(this.isOn === 'vd' || this.isOn === 'agcrm-wp') return; // don't save from some platforms
        let url = (this.accountPrefix ? 'https://'+this.accountPrefix+'.autoglasscrm.com' : '')+'/vindecoder/savefilterresults';
        let data = {
            tid: this.trackingId,
            features_selected: Object.keys(this.selectedFeaturesTitles).join(', '),
            part_selected: (this.partsActiveCount === 1 ? this.lastPartActive.id : '')
        };
        this.ajax('POST', url, data)
    }

    /**
     * If user filtered to a single part, save result to their vindecoder search history
     */
    saveToSearchHistory() {
        if(this.isOn === 'vd' || this.isOn === 'agcrm-wp') return; // don't save from some platforms
        let url = (this.accountPrefix ? 'https://'+this.accountPrefix+'.autoglasscrm.com' : '')+'/vindecoder/savefromfiltering';
        if(this.partsActiveCount !== 1) return;
        let part = this.lastPartActive,
            data = {
                searchid: this.searchid,
                type: this.glassType,
                opt: this.glassOpt,
                dpns: part.dealer_part_nums.join(', '),
                pn: part.part_number,
                desc: part.description,
                vurl: (part.videos.length ? part.videos[0].url : ''),
                vid: (part.videos.length ? part.videos[0].playid : ''),
            };
        this.ajax('POST', url, data);
    }

    /**
     * AJAX post JSON to endpoint
     * @param string request method
     * @param string url to post to
     * @param object data to post
     */
    ajax(method, url, data) {
        return new Promise(function(resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open(method, url);
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
            xhr.onload = function() {
                if(xhr.status === 200) {
                    resolve(xhr.response);
                } else {
                    reject();
                }
            }
            xhr.send((data ? JSON.stringify(data) : null));
        });
    }

    /**
     * Get part container element in UI results set
     */
    partUI(id) {
        return document.getElementById('part-'+id);
    }

    /**
     * @return string of selected features
     */
    get selectedFeaturesTitlesStr() {
        let str = '';
        for(let k in this.selectedFeaturesTitles) {
            str += this.selectedFeaturesTitles[k]+', ';
        }
        return str.replace(/,\s$/, '');
    }

    /**
     * @return int glass type id
     */
    get glassType() {
        return (this._glassType ? this._glassType : 4); // default to 4, for windshield
    }
    get glassOpt() {
        return (this._glassOpt ? this._glassOpt : null); // default to null, for windshield
    }

    /**
     * @return array of slugs for all features being filtered
     */
    get features() {
        if(!this._features) {
            this._features = Object.keys(this.partsByFeature);
        }
        return this._features;
    }

    /**
     * @return count of active parts
     */
    get partsActiveCount() {
        return Object.keys(this.partsActive).length;
    }

    /**
     * @return last active part
     */
    get lastPartActive() {
        let params = Object.keys(this.partsActive);
        if(params.length === 1) {
            return this.partsActive[params[0]];
        } else {
            return null;
        }
    }

    /**
     * @return boolean can these results be filtered?
     */
    canFilter() {
        this.clearIrrelevantFeatures();
        return this.canFilterInternal && this.features.length;
    }
}

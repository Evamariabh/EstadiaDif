const PANE_SWIPE_DURATION = 200;
const MODAL_DISMISS_DURATION = 300;
let paneAnimationEnded = true;

window.addEventListener("load", function() {
    ReloadGUSITables();
    let mainNav = document.querySelector('nav');
    if (mainNav) {
        mainNav.querySelectorAll('.nav-link:not(.nav-btn-highlight)').forEach(link => {
            link.addEventListener('click', function(e) {
                if (paneAnimationEnded) {
                    mainNav.querySelectorAll('.nav-link').forEach(navLink => {
                        navLink.classList.remove('active');
                    });
                    link.classList.add('active');
                    if (link.getAttribute('data-show-pane') != null && link.getAttribute('data-location') != null) {
                        let Target = document.querySelector(link.getAttribute('data-show-pane'));
                        let Container = document.querySelector(link.getAttribute('data-location'));
                        let Current = document.querySelector(link.getAttribute('data-location') + '>.pane.show');
                        if (Target && Container && Current) {
                            if (Target != Current) {
                                SwipePane(Current, Target);
                            }
                        }
                    }
                }
            });
        });;
    }
    let navToggler = document.querySelector('.nav-close');
    if (navToggler) {
        navToggler.addEventListener('click', function(e) {
            if (mainNav) {
                mainNav.classList.toggle('show');
            }
        });
    }
    document.querySelectorAll('[data-modal-close]').forEach(btnClose => {
        btnClose.addEventListener('click', function(e) {
            DismissModal(btnClose.getAttribute('data-modal-close'));
        });
    });
    document.querySelectorAll('[data-modal-show]').forEach(btnOpen => {
        btnOpen.addEventListener('click', function(e) {
            ShowModal(btnOpen.getAttribute('data-modal-show'));
        });
    });
    document.querySelectorAll('.step-indicator').forEach(stepIndicator => {
        let NumSteps = stepIndicator.getAttribute('data-steps');
        if (NumSteps) {
            if (Number.parseInt(NumSteps) > 0) {
                for (let index = 0; index < NumSteps; index++) {
                    let StepItem = document.createElement('div');
                    StepItem.className = 'step';
                    if (index == 0) {
                        StepItem.classList.add('active');
                    }
                    stepIndicator.appendChild(StepItem);
                }
            } else {
                console.log("StepIndicator", stepIndicator, ' no tiene un número válido de pasos');
            }
        } else {
            console.log("StepIndicator: ", stepIndicator, ' no tiene un número de pasos especificados');
        }
        if (stepIndicator.getAttribute('allow-change')) {
            stepIndicator.querySelectorAll('.step').forEach(stepItem => {
                stepItem.addEventListener('click', () => {
                    StepIndicatorChange(Array.prototype.indexOf.call(stepItem.parentElement.children, stepItem) + 1, stepIndicator.getAttribute('allow-change'));
                });
            });
        }
    });
    document.querySelectorAll('[data-step-jump]').forEach(stepJump => {
        let step = stepJump.getAttribute('data-step-jump');
        let Indicator = stepJump.getAttribute('data-indicator');
        stepJump.addEventListener('click', () => {
            StepIndicatorChange(step, Indicator);
        });
    });
    UpdateStepContainers();
});


function SwipePane(targetOut, targetIn) {
    paneAnimationEnded = false;
    targetOut.classList.add('swipeout');
    targetOut.classList.remove('show');
    setTimeout(() => {
        targetOut.classList.remove('swipeout');
        targetIn.classList.add('swipein');
        setTimeout(() => {
            targetIn.classList.add('show');
            targetIn.classList.remove('swipein');
            paneAnimationEnded = true;
        }, PANE_SWIPE_DURATION);
    }, PANE_SWIPE_DURATION);
}

function DismissModal(selector) {
    let Target = document.querySelector(selector);
    if (Target) {
        Target.classList.add('hiding');
        Target.classList.remove('show');
        setTimeout(() => {
            Target.classList.remove('hiding');
            Target.classList.add('hidden');
        }, MODAL_DISMISS_DURATION);
    }
}

function ShowModal(selector) {
    let Target = document.querySelector(selector);
    if (Target) {
        Target.classList.remove('hidden');
        setTimeout(() => {
            Target.classList.add('opening');
            setTimeout(() => {
                Target.classList.remove('opening');
                Target.classList.add('show');
            }, MODAL_DISMISS_DURATION);
        }, 5);
    }
}

function StepIndicatorChange(step, indicator) {
    let _Step = Number.parseInt(step);
    let _Indicator = document.querySelector(indicator);
    if (_Indicator) {
        let AllSteps = _Indicator.querySelectorAll('.step').length;
        if (_Step <= AllSteps) {
            _Indicator.querySelectorAll('.step').forEach(stepChildren => {
                stepChildren.classList.remove('active');
            });
            _Indicator.querySelectorAll('.step')[_Step - 1].classList.add("active")
            _Indicator.setAttribute('data-active', _Step);
            UpdateStepContainers();
        } else {
            console.error("El paso especificado está fuera de los límites del indicador.", _Step, document.querySelector(indicator));
        }
    } else {
        console.error("El indicador especificado no existe");
    }
}

function UpdateStepContainers() {
    document.querySelectorAll('.step-container[data-step-indicator-parent][data-show-on]').forEach(stepItem => {
        let Parent = document.querySelector(stepItem.getAttribute('data-step-indicator-parent'));
        let ShowOn = stepItem.getAttribute('data-show-on');
        if (Parent && ShowOn) {
            let ParentShows = Parent.getAttribute('data-active');
            if (ParentShows) {
                if (ParentShows == ShowOn) {
                    stepItem.style.display = "block";
                } else {
                    stepItem.style.display = "none";
                }
            }
        }
    });
}


function ReloadGUSITables() {
    document.querySelectorAll('table.gusi-table').forEach(GusiTable => {
        let TableTitles = GusiTable.querySelectorAll('thead>tr>th');
        GusiTable.querySelectorAll('tbody>tr').forEach(TableRow => {
            if (TableTitles) {
                for (let index = 0; index < TableTitles.length; index++) {
                    TableRow.children[index].setAttribute('data-title', TableTitles[index].innerText);
                }
            }
        });
    });
}
@extends('layouts.app')
@section('content')
    <div class="bg-white p-3 rounded-sm">
        <div>
            <h4 class="py-2 font-bold flex flex-row items-center ">
                <span class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                    <i class="ri-user-line text-primary"></i>
                </span>
                Employee Information
            </h4>
            <hr />
        </div>
    </div>
    <div class="min-h-screen  mt-5">
        <div class="relative h-64 bg-gray-200 rounded-md max-sm:hidden">
            <img src="{{ url('assets/image/profile-12.png') }}" alt="Header Background"
                class="object-cover w-full h-full  rounded-md" />
        </div>
        <div class="relative container mx-auto md:px-4">
            <div class="flex gap-4 md:flex-row flex-col">
                <div class="relative bg-white border  rounded-lg  md:w-1/3 w-full p-2 md:p-6 md:-mt-16 h-[700px]">
                    <div class="flex flex-col items-center">
                        <img class="w-24 h-24 rounded-full object-cover border" src="{{ url('assets/image/profile-12.png') }}"
                            alt="Profile" />
                        <h2 class="mt-4 text-lg font-bold">Rakesh Singh</h2>
                        <p class="text-gray-500d">Software Developmer 1</p>
                        <p class="text-sm text-gray-500 flex items-center mt-1">
                            Noida, India
                        </p>
                    </div>

                    <div class="mt-6">
                        <div class="py-2">
                            <h4 class="py-2 font-semibold flex flex-row items-center pb-2">
                                <span class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                    <i class="ri-file-user-line text-primary"></i>
                                </span>
                                Personal Information
                            </h4>
                            <hr />
                        </div>
                        <p class="mt-2 text-sm text-gray-600 py-2 "><strong>Name : </strong> Rakesh Singh
                        </p>

                        <p class="text-sm text-gray-600 py-2"><strong>Designation : </strong> Software
                            Developer 1</p>

                        <p class="text-sm text-gray-600 py-2"><strong>Email : </strong>
                            rakesh.ks@amysoftech.in</p>

                        <p class="text-sm text-gray-600 py-2"><strong>Phone : </strong> +91 999999999</p>

                        <p class="text-sm text-gray-600 py-2"><strong>Experience : </strong> 10 Years</p>

                        <p class="text-sm text-gray-600 py-2"><strong>Age : </strong> 25</p>
                        <p class="text-sm text-gray-600 py-2"><strong>Company : </strong> Amysoftech</p>
                    </div>
                </div>
                <div class="flex-1  bg-white  rounded-lg  md:p-6 p-2 md:-mt-16">
                    <div class="tabs mb-2 my-3">
                        <div class="flex gap-2">
                            <button type="button" id="tab1"
                                class="tab-btn  py-2 px-6 -mb-px border-b-2 focus:outline-none  rounded-sm"
                                onclick="changeTab(1)">Activity</button>
                            <button type="button" id="tab2"
                                class="tab-btn  py-2 px-6 -mb-px border-b-2 focus:outline-none   rounded-sm"
                                onclick="changeTab(2)">Comment</button>
                            <button type="button" id="tab3"
                                class="tab-btn  py-2 px-6 -mb-px border-b-2 focus:outline-none   rounded-sm"
                                onclick="changeTab(3)">Workflow</button>
                        </div>
                    </div>
                    <div class="tab-content border-t rounded-sm p-2">
                        <div id="content1" class="tab-pane hidden">
                            <div class=" p-4 rounded-md border-[1px] border-gray-100">
                                <div class="py-2">
                                    <h4 class=" font-semibold flex flex-row items-center pb-2">
                                        <span
                                            class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                            <i class="ri-user-line text-primary"></i>
                                        </span>
                                        About Information
                                    </h4>
                                    <hr />
                                </div>
                                <span class="text-sm text-gray-500">
                                    Hello, I'm [Your Name], a dedicated [Your Profession/Interest] based in [Your Location].
                                    I
                                    have a genuine passion for [Your Hobbies/Interests] and enjoy delving into the nuances
                                    of
                                    [Your Industry/Field].

                                    Specializing in [Your Specialization/Area of Expertise], I strive to infuse innovation
                                    into
                                    every project I undertake. With a track record of [Key Achievements] and valuable
                                    experiences, I'm committed to continual growth and eagerly anticipate the opportunities
                                    that
                                    lie ahead.
                                </span>
                                <div class="py-2">
                                    <h4 class="py-2 font-semibold flex flex-row items-center pb-2 ">
                                        <span
                                            class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                            <i class="ri-contacts-book-2-line text-primary"></i>
                                        </span>
                                        Contact Information
                                    </h4>
                                    <hr />
                                </div>

                                <p class="text-sm text-gray-600 py-2 flex items-center"><strong class="flex items-center">
                                        <span
                                            class="w-8 h-8 bg-blue-100 rounded-full flex justify-center items-center p-3 mr-2">
                                            <i class="ri-mail-line text-primary"></i>
                                        </span>Email : </strong> rakesh.ks@amysoftech.in
                                </p>
                                <p class="text-sm text-gray-600 py-2 flex items-center"><strong class="flex items-center">
                                        <span
                                            class="w-8 h-8 bg-red-100 rounded-full flex justify-center items-center p-3 mr-2">
                                            <i class="ri-phone-line text-red-500"></i>
                                        </span>Phone : </strong> +91 783273289
                                </p>
                                <p class="text-sm text-gray-600 py-2 flex items-center"><strong class="flex items-center">
                                        <span
                                            class="w-8 h-8 bg-purple-100 rounded-full flex justify-center items-center p-3 mr-2">
                                            <i class="ri-map-pin-2-line text-purple-500"></i>
                                        </span>Website : </strong> www.yourwebsite.com
                                </p>
                                <p class="text-sm text-gray-600 py-2 flex items-center"><strong class="flex items-center">
                                        <span
                                            class="w-8 h-8 bg-yellow-100 rounded-full flex justify-center items-center p-3 mr-2">
                                            <i class="ri-home-4-line text-yellow-400"></i>
                                        </span>Location : </strong> Noida, India
                                </p>
                                <div class="py-2">
                                    <h4 class="py-2 font-semibold flex flex-row items-center pb-2 ">
                                        <span
                                            class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                            <i class="ri-stack-line text-primary"></i>
                                        </span>
                                        Skill Information
                                    </h4>
                                    <hr />
                                </div>
                                <div class="">
                                    <span
                                        class="bg-gray-100 text-gray-500 text-sm font-semibold me-2 px-2.5 py-0.5 rounded">Leadership</span>
                                    <span
                                        class="bg-gray-100 text-gray-500 text-sm font-semibold me-2 px-2.5 py-0.5 rounded">Project
                                        Management</span>
                                    <span
                                        class="bg-gray-100 text-gray-500 text-sm font-semibold me-2 px-2.5 py-0.5 rounded">Communication</span>
                                    <span
                                        class="bg-gray-100 text-gray-500 text-sm font-semibold me-2 px-2.5 py-0.5 rounded">Team
                                        Building</span>
                                    <span
                                        class="bg-gray-100 text-gray-500 text-sm font-semibold me-2 px-2.5 py-0.5 rounded">Problem
                                        Solving</span>
                                    <span
                                        class="bg-gray-100 text-gray-500 text-sm font-semibold me-2 px-2.5 py-0.5 rounded">Decision
                                        Making</span>
                                    <span
                                        class="bg-gray-100 text-gray-500 text-sm font-semibold me-2 px-2.5 py-0.5 rounded">Conflick
                                        Resolution</span>
                                    <span
                                        class="bg-gray-100 text-gray-500 text-sm font-semibold me-2 px-2.5 py-0.5 rounded">Continuous
                                        Improvment </span>
                                    <span
                                        class="bg-gray-100 text-gray-500 text-sm font-semibold me-2 px-2.5 py-0.5 rounded">Adapablility</span>
                                </div>
                                <div class="py-2">
                                    <h4 class="py-2 font-semibold flex flex-row items-center pb-2 ">
                                        <span
                                            class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                            <i class="ri-open-source-line text-primary"></i>
                                        </span>
                                        Social Network
                                    </h4>
                                    <hr />
                                </div>
                                <div class="flex  gap-4 flex-wrap flex-row">
                                    <div class="flex">
                                        <span
                                            class="w-10 h-10 bg-blue-300 hover:bg-primary text-white rounded-sm flex justify-center items-center p-3 mr-2">
                                            <i class="ri-github-line"></i>
                                        </span>
                                        <span>
                                            <p class="font-bold text-gray-500 text-sm">Github</p>
                                            <small class="hover:text-primary cursor-pointer hover:font-bold">
                                                github.com/spruko</small>
                                        </span>
                                    </div>
                                    <div class="flex">
                                        <span
                                            class="w-10 h-10 bg-pink-300 hover:bg-pink-500 text-white text- rounded-sm flex justify-center items-center p-3 mr-2">
                                            <i class="ri-twitter-x-line"></i>
                                        </span>
                                        <span>
                                            <p class="font-bold text-gray-500 text-sm">Twitter</p>
                                            <small class="hover:text-primary cursor-pointer hover:font-bold">
                                                twitter.com/spruko.me
                                            </small>
                                        </span>
                                    </div>
                                    <div class="flex">
                                        <span
                                            class="w-10 h-10 bg-violet-300 hover:bg-violet-500 text-white rounded-sm flex justify-center items-center p-3 mr-2">
                                            <i class="ri-linkedin-line"></i>
                                        </span>
                                        <span>
                                            <p class="font-bold text-gray-500 text-sm">Linkedin</p>
                                            <small class="hover:text-primary cursor-pointer hover:font-bold">
                                                linkedin.com/in/spruko</small>
                                        </span>
                                    </div>
                                    <div class="flex">
                                        <span
                                            class="w-10 h-10 bg-red-300 hover:bg-red-500 text-white rounded-sm flex justify-center items-center p-3 mr-2">
                                            <i class="ri-passport-line"></i>
                                        </span>
                                        <span>
                                            <p class="font-bold text-gray-500 text-sm">My Portfolio</p>
                                            <small class="hover:text-primary cursor-pointer hover:font-bold">
                                                spruko.com/</small>
                                        </span>
                                    </div>
                                </div>



                            </div>
                        </div>
                        <div id="content2" class="tab-pane hidden">
                            <div class=" p-4 rounded-md border-[1px] border-gray-100">
                                <form>
                                    <div class="flex gap-7 my-3 items-center md:flex-row flex-col">
                                        <div class="border-2 border-dashed p-5 flex justify-center flex-col items-center rounded-md cursor-pointer"
                                            id="upload-box">
                                            <input type="file" id="image-upload" accept=".jpg, .jpeg, .png"
                                                class="hidden" />
                                            <div id="image-container" class="relative">
                                                <i class="ri-add-circle-line" id="icon-upload"></i>
                                                <img id="profile-image" src="" alt="Profile Image"
                                                    class="hidden w-16 h-16 rounded-full border-2 object-cover" />
                                                <button id="delete-btn"
                                                    class="hidden absolute top-0 right-0 bg-red-500 text-white rounded-full p-1">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                            <p>Profile Photo</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-400 py-2">For better preview recommended size is
                                                450px
                                                *
                                                450px. <br />
                                                (Only JPEG, JPG and PNG Allowed, Max size 1MB)
                                            </p>
                                        </div>
                                    </div>

                                    <div class="py-2">
                                        <h4 class=" font-semibold flex flex-row items-center pb-2">
                                            <span
                                                class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                                <i class="ri-user-line text-primary"></i>
                                            </span>
                                            Personal Information
                                        </h4>
                                        <hr />
                                    </div>
                                    <div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">First
                                                Name</label>
                                            <input type="text"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs "
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Last
                                                Name</label>
                                            <input type="text"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs "
                                                required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Designation
                                            </label>
                                            <input type="text"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs "
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Experience
                                            </label>
                                            <input type="text"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs "
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Age
                                            </label>
                                            <input type="number"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs "
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Company
                                            </label>
                                            <input type="text"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs "
                                                required>
                                        </div>

                                    </div>

                                    <div class="py-2">
                                        <h4 class="py-2 font-semibold flex flex-row items-center pb-2 ">
                                            <span
                                                class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                                <i class="ri-user-6-line text-primary"></i>
                                            </span>
                                            About Information
                                        </h4>
                                        <hr />
                                    </div>
                                    <div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Brief
                                                Information
                                            </label>
                                            <textarea type="text"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs " required></textarea>
                                        </div>
                                    </div>

                                    <div class="py-2">
                                        <h4 class=" font-semibold flex flex-row items-center pb-2">
                                            <span
                                                class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                                <i class="ri-contacts-book-2-line text-primary"></i>
                                            </span>
                                            Contact Information
                                        </h4>
                                        <hr />
                                    </div>
                                    <div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Email
                                            </label>
                                            <input type="email"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs "
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Phone
                                                Number</label>
                                            <input type="number"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs "
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Website
                                            </label>
                                            <input type="text"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs "
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Location
                                            </label>
                                            <input type="text"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs "
                                                required>
                                        </div>

                                    </div>

                                    <div class="py-2">
                                        <h4 class="py-2 font-semibold flex flex-row items-center pb-2 ">
                                            <span
                                                class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                                <i class="ri-stack-line text-primary"></i>
                                            </span>
                                            Skill Information
                                        </h4>
                                        <hr />
                                    </div>
                                    <div>
                                        <div class="mb-3">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Skills
                                            </label>
                                            <textarea type="text"
                                                class="block w-full p-2 text-gray-900 border border-gray-300 rounded-sm bg-transparent text-xs " required></textarea>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-2">
                                        <button type="reset"
                                            class="btn-primary-sm">Reset</button>
                                        <button type="submit"
                                            class="btn-success-sm  ">Update
                                            Information
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <div id="content3" class="tab-pane hidden">
                            <div class=" p-4  rounded-md border-[1px] border-gray-100">
                                <div class="py-2">
                                    <h4 class="py-2 font-semibold flex flex-row items-center pb-2 ">
                                        <span
                                            class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                            <i class="ri-notification-3-line text-primary"></i>
                                        </span>
                                        Notification Settings
                                    </h4>
                                    <hr />
                                </div>
                                <div>
                                    <div class="flex justify-between py-2">
                                        <div>
                                            <p class="font-semibold text-sm">Push Notifications</p>
                                            <small class="text-gray-400 font-semibold">Alerts sent to the user's mobile
                                                device
                                                or desktop.</small>
                                        </div>
                                        <div>
                                            <label
                                                class="relative inline-block h-7 w-[48px] cursor-pointer rounded-full bg-gray-400 transition [-webkit-tap-highlight-color:_transparent] has-[:checked]:bg-[#1976D2]">
                                                <input type="checkbox" id="AcceptConditions" class="peer sr-only" />
                                                <span
                                                    class="absolute inset-y-0 start-0 m-1 size-5 rounded-full bg-gray-300 ring-[5px] ring-inset ring-white transition-all peer-checked:start-7  peer-checked:w-2 peer-checked:bg-white peer-checked:ring-transparent"></span>
                                            </label>

                                        </div>
                                    </div>
                                    <div class="flex justify-between py-2">
                                        <div>
                                            <p class="font-semibold text-sm">Email Notifications</p>
                                            <small class="text-gray-400 font-semibold">Messages sent to the user's email
                                                address.
                                            </small>
                                        </div>
                                        <div>
                                            <label
                                                class="relative inline-block h-7 w-[48px] cursor-pointer rounded-full bg-gray-400 transition [-webkit-tap-highlight-color:_transparent] has-[:checked]:bg-[#1976D2]">
                                                <input type="checkbox" id="AcceptConditions" class="peer sr-only" />
                                                <span
                                                    class="absolute inset-y-0 start-0 m-1 size-5 rounded-full bg-gray-300 ring-[5px] ring-inset ring-white transition-all peer-checked:start-7  peer-checked:w-2 peer-checked:bg-white peer-checked:ring-transparent"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex justify-between py-2">
                                        <div>
                                            <p class="font-semibold text-sm">In-App Notifications

                                            </p>
                                            <small class="text-gray-400 font-semibold">Alerts that appear within the
                                                application interface.
                                            </small>
                                        </div>
                                        <div>
                                            <label
                                                class="relative inline-block h-7 w-[48px] cursor-pointer rounded-full bg-gray-400 transition [-webkit-tap-highlight-color:_transparent] has-[:checked]:bg-[#1976D2]">
                                                <input type="checkbox" id="AcceptConditions" class="peer sr-only" />
                                                <span
                                                    class="absolute inset-y-0 start-0 m-1 size-5 rounded-full bg-gray-300 ring-[5px] ring-inset ring-white transition-all peer-checked:start-7  peer-checked:w-2 peer-checked:bg-white peer-checked:ring-transparent"></span>
                                            </label>

                                        </div>
                                    </div>
                                    <div class="flex justify-between py-2">
                                        <div>
                                            <p class="font-semibold text-sm">SMS Notifications
                                            </p>
                                            <small class="text-gray-400 font-semibold">Text messages sent to the user's
                                                mobile
                                                phone.
                                                .</small>
                                        </div>
                                        <div>
                                            <label
                                                class="relative inline-block h-7 w-[48px] cursor-pointer rounded-full bg-gray-400 transition [-webkit-tap-highlight-color:_transparent] has-[:checked]:bg-[#1976D2]">
                                                <input type="checkbox" id="AcceptConditions" class="peer sr-only" />
                                                <span
                                                    class="absolute inset-y-0 start-0 m-1 size-5 rounded-full bg-gray-300 ring-[5px] ring-inset ring-white transition-all peer-checked:start-7  peer-checked:w-2 peer-checked:bg-white peer-checked:ring-transparent"></span>
                                            </label>

                                        </div>
                                    </div>
                                </div>

                                <div class="py-2">
                                    <h4 class="py-2 font-semibold flex flex-row items-center pb-2 ">
                                        <span
                                            class="w-8 h-8 bg-neutral rounded-full flex justify-center items-center p-3 mr-2">
                                            <i class="ri-git-repository-private-line text-primary"></i>
                                        </span>
                                        Security Settings
                                    </h4>
                                    <hr />
                                </div>
                                <div>
                                    <div class="flex md:justify-between flex-col md:flex-row py-2">
                                        <div>
                                            <p class="font-semibold text-sm">Verification</p>
                                            <small class="text-gray-400 font-semibold">Control how your profile information
                                                is
                                                verified for security purposes
                                                .</small>
                                        </div>
                                        <div class="flex justify-between  gap-2 flex-col md:flex-row">
                                            <div class="flex justify-center items-center gap-2">
                                                <input type="checkbox" class="cursor-pointer " />
                                                <small>Email</small>
                                            </div>
                                            <div class="flex justify-center items-center gap-2">
                                                <input type="checkbox" class="cursor-pointer " />
                                                <small>SMS</small>
                                            </div>
                                            <div class="flex justify-center items-center gap-2">
                                                <input type="checkbox" class="cursor-pointer " />
                                                <small>Phone</small>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="flex justify-between py-2 flex-col md:flex-row">
                                        <div>
                                            <p class="font-semibold text-sm">Login Verification

                                            </p>
                                            <small class="text-gray-400 font-semibold">This helps protect accounts from
                                                unauthorized access, even if a password is compromised.
                                            </small>
                                        </div>
                                        <div>
                                            <a href='/' class="text-sm text-primary underline hover:font-bold">Setup
                                                Login Verification
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex justify-between py-2 flex-col md:flex-row">
                                        <div>
                                            <p class="font-semibold text-sm">In-App Notifications
                                            </p>
                                            <small class="text-gray-400 font-semibold">Alerts that appear within the
                                                application interface.
                                            </small>
                                        </div>
                                        <div>
                                            <div class="flex justify-center items-center gap-2">
                                                <input type="checkbox" class="cursor-pointer " />
                                                <small>Require Personal Details
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('layouts.tab_js')
@endsection

<template>
    <div class="user-profile">
        <div class="profile-header">
            <div class="profile-cover" :style="{ backgroundImage: `url('${user.cover_photo}')` }"></div>
            <div class="profile-avatar-section">
                <img :src="user.profile_photo" :alt="user.name" class="profile-avatar">
                <div class="profile-info">
                    <h3 class="profile-name">{{ user.name }}</h3>
                    <p class="profile-title">{{ user.job_title }}</p>
                    <p class="profile-location">
                        <i class="bi bi-geo-alt"></i>
                        {{ user.location }}
                    </p>
                </div>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-section">
                <h5 class="section-title">About</h5>
                <p class="section-text">{{ user.bio }}</p>
            </div>

            <div class="profile-section">
                <h5 class="section-title">Skills</h5>
                <div class="skills-list">
                    <span v-for="skill in user.skills" :key="skill" class="skill-tag">
                        {{ skill }}
                    </span>
                </div>
            </div>

            <div class="profile-section">
                <h5 class="section-title">Experience</h5>
                <div v-for="exp in user.experience" :key="exp.id" class="experience-item">
                    <div class="exp-header">
                        <h6 class="exp-title">{{ exp.position }}</h6>
                        <span class="exp-duration">{{ exp.duration }}</span>
                    </div>
                    <p class="exp-company">{{ exp.company }}</p>
                    <p class="exp-description">{{ exp.description }}</p>
                </div>
            </div>

            <div class="profile-section">
                <h5 class="section-title">Education</h5>
                <div v-for="edu in user.education" :key="edu.id" class="education-item">
                    <div class="edu-header">
                        <h6 class="edu-degree">{{ edu.degree }}</h6>
                        <span class="edu-year">{{ edu.year }}</span>
                    </div>
                    <p class="edu-school">{{ edu.school }}</p>
                </div>
            </div>

            <div class="profile-actions">
                <button @click="editProfile" class="btn btn-edit">Edit Profile</button>
                <button @click="viewResume" class="btn btn-resume">View Resume</button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: 'UserProfile',
    props: {
        userId: {
            type: Number,
            required: true,
        },
    },
    setup(props, { emit }) {
        const user = ref({
            name: 'John Doe',
            job_title: 'Senior Software Engineer',
            location: 'Dhaka, Bangladesh',
            profile_photo: 'https://via.placeholder.com/150',
            cover_photo: 'https://via.placeholder.com/1200x300',
            bio: 'Experienced software engineer with 5+ years of experience in full-stack development. Passionate about building scalable applications and mentoring junior developers.',
            skills: ['Vue.js', 'Laravel', 'React', 'Node.js', 'Python', 'AWS'],
            experience: [
                {
                    id: 1,
                    position: 'Senior Software Engineer',
                    company: 'Tech Corp',
                    duration: '2021 - Present',
                    description: 'Led development of microservices architecture',
                },
                {
                    id: 2,
                    position: 'Full Stack Developer',
                    company: 'Digital Solutions',
                    duration: '2019 - 2021',
                    description: 'Developed and maintained web applications',
                },
            ],
            education: [
                {
                    id: 1,
                    degree: 'Bachelor of Science in Computer Science',
                    school: 'Dhaka University',
                    year: '2019',
                },
            ],
        });

        const editProfile = () => {
            emit('edit');
        };

        const viewResume = () => {
            emit('view-resume');
        };

        return {
            user,
            editProfile,
            viewResume,
        };
    },
};
</script>

<style scoped>
.user-profile {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.profile-header {
    position: relative;
}

.profile-cover {
    width: 100%;
    height: 240px;
    background-size: cover;
    background-position: center;
    background-color: #e5e7eb;
}

.profile-avatar-section {
    padding: 0 2rem 2rem 2rem;
    display: flex;
    gap: 1.5rem;
    align-items: flex-end;
    transform: translateY(-60px);
    position: relative;
    z-index: 1;
}

.profile-avatar {
    width: 140px;
    height: 140px;
    border-radius: 12px;
    border: 4px solid white;
    object-fit: cover;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
    flex-shrink: 0;
}

.profile-info {
    margin-bottom: 0.5rem;
}

.profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.profile-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2563eb;
    margin: 0 0 0.5rem 0;
}

.profile-location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
    color: #6b7280;
    margin: 0;
}

.profile-content {
    padding: 0 2rem 2rem 2rem;
}

.profile-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 1rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f3f4f6;
}

.section-text {
    color: #4b5563;
    line-height: 1.6;
    margin: 0;
}

.skills-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.skill-tag {
    display: inline-block;
    background-color: #eff3fb;
    color: #2563eb;
    padding: 0.5rem 1rem;
    border-radius: 999px;
    font-size: 0.9rem;
    font-weight: 500;
}

.experience-item,
.education-item {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f3f4f6;
}

.experience-item:last-child,
.education-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.exp-header,
.edu-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.exp-title,
.edu-degree {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.exp-duration,
.edu-year {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 600;
}

.exp-company,
.edu-school {
    font-size: 0.9rem;
    color: #2563eb;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.exp-description {
    font-size: 0.9rem;
    color: #4b5563;
    margin: 0;
    line-height: 1.5;
}

.profile-actions {
    display: flex;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 2px solid #f3f4f6;
}

.btn {
    flex: 1;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
}

.btn-edit {
    background-color: #2563eb;
    color: white;
}

.btn-edit:hover {
    background-color: #1d4ed8;
    transform: translateY(-1px);
}

.btn-resume {
    background-color: #f3f4f6;
    color: #374151;
    border: 1px solid #e5e7eb;
}

.btn-resume:hover {
    background-color: #e5e7eb;
    border-color: #d1d5db;
}
</style>

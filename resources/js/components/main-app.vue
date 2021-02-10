<style scoped>
    .layout-con{
        height: 100%;
        width: 100%;
    }
    .menu-item span{
        display: inline-block;
        overflow: hidden;
        width: 69px;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: bottom;
        transition: width .2s ease .2s;
    }
    .menu-item i{
        transform: translateX(0px);
        transition: font-size .2s ease, transform .2s ease;
        vertical-align: middle;
        font-size: 16px;
    }
    .collapsed-menu span{
        width: 0px;
        transition: width .2s ease;
    }
    .collapsed-menu i{
        transform: translateX(5px);
        transition: font-size .2s ease .2s, transform .2s ease .2s;
        vertical-align: middle;
        font-size: 22px;
    }
    .custom{
      height: 600px;
      overflow-y: scroll;
    }
    ::-webkit-scrollbar {
            display: none;
        }
</style>
<template>
    <div class="layout">
        <Layout :style="{minHeight: '100vh'}">
            <Sider class="bg-white" collapsible :collapsed-width="100" v-model="isCollapsed">
                

                   <Menu active-name="1-1" theme="primary" width="auto" :class="menuitemClasses" :accordion="true">
                       <MenuItem name="1-1" to="/home/">
                          <Icon type="ios-home-outline" />
                           <span>Home</span>
                       </MenuItem>

                       <Submenu name="2-1">
                        <template slot="title">
                          <Icon type="ios-book-outline" />
                           <span>Notes</span>
                        </template>
                          <MenuItem name="2-1-1" to="/notes/">My Notes</MenuItem>
                          <MenuItem name="2-1-2" to="/sharedNotes/">Shared Notes</MenuItem>
                       </Submenu>


                       <Submenu name="3-1">
                        <template slot="title">
                           <Icon type="ios-chatboxes" />
                           <span>Chats</span>
                        </template>
                          <MenuItem name="3-1-1" to="/chat/">Chat</MenuItem>

                       </Submenu>      

                      <!--  <MenuItem name="4-1" to="/class_register/">
                          <Icon type="ios-create-outline" />
                           <span>Register</span>
                       </MenuItem> -->

                       <div v-if="user.account_type == 'Lecturer'">
                        <Submenu name="5-1">
                         <template slot="title">
                            <Icon type="md-school" />
                            <span>School</span>
                         </template>
                           <MenuItem name="5-1-1" to="/lessonPlan/">Lesson Plans</MenuItem>
                          <!--  <MenuItem name="5-1-2" to="/my_register/">Class Attendance</MenuItem> -->
                           <MenuItem name="5-1-3" to="/classes/">
                              Classes
                           </MenuItem>
                           <MenuItem name="5-1-4" to="/ask_reviews/">
                               Add Review
                           </MenuItem>
                           <MenuItem name="5-1-5" to="/cloud_source/">Reviews & threads</MenuItem>
                        </Submenu>                         
                           
                       </div> 

                       <div v-if="user.account_type == 'Student'">
                        <Submenu name="7-1">
                         <template slot="title">
                            <Icon type="md-school" />
                            <span>School</span>
                         </template>
                           <MenuItem name="7-1-1" to="/available_classes/">Available Classes</MenuItem>
                           <MenuItem name="7-1-2" to="/my_classes/">My Classes</MenuItem>
                           <MenuItem name="7-1-3" to="/responses/">Reviews & threads</MenuItem>
                        </Submenu>
                       </div>                       
                   </Menu> 

                    
                
            </Sider>
            <Layout class="custom">
                <Content  :style="{padding: '0 18px 8px'}" ><router-view></router-view>
                </Content>
            </Layout>
        </Layout>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                isCollapsed: false
            };
        },
        props:['user'],
        computed: {
            menuitemClasses: function () {
                return [
                    'menu-item',
                    this.isCollapsed ? 'collapsed-menu' : ''
                ]
            }
        }
    }
</script>
